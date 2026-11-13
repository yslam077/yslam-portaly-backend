<?php

namespace App\Providers;

use App\Services\Sms\LogSmsService;
use App\Services\Sms\SmsService;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\InfoObject;
use Dedoc\Scramble\Support\Generator\MediaType;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Support\Generator\Path;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\SecurityRequirement;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\Server;
use Dedoc\Scramble\Support\Generator\Types\BooleanType;
use Dedoc\Scramble\Support\Generator\Types\IntegerType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsService::class, LogSmsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewApiDocs', fn ($user = null) => true);

        Scramble::afterOpenApiGenerated(function (OpenApi $openApi): void {
            $openApi->info = InfoObject::make(config('scramble.info.title', 'Yslam Portaly API'))
                ->setVersion(config('scramble.info.version', '1.0.0'))
                ->setDescription(config('scramble.info.description', ''));

            $openApi->servers = [
                new Server('http://localhost:8000'),
                (new Server(rtrim((string) config('app.url'), '/')))
                    ->setDescription('Production URL из APP_URL'),
            ];

            $openApi->components->addSecurityScheme(
                'BearerAuth',
                SecurityScheme::http('bearer')->setDescription('Токен Sanctum, полученный через OTP.')
            );

            $documentation = $this->apiDocumentation();

            foreach ($openApi->paths as $path) {
                if (! $path instanceof Path) {
                    continue;
                }

                $path->path = 'api/v1/'.ltrim($path->path, '/');

                foreach ($path->operations as $operation) {
                    $key = strtolower($operation->method).' '.ltrim(substr($path->path, strlen('api/v1/')), '/');
                    $details = $documentation[$key] ?? null;

                    if ($details === null) {
                        continue;
                    }

                    $operation->summary($details['summary']);
                    $operation->description($details['description']);
                    $operation->setTags([$details['tag']]);
                    $operation->security = $details['auth']
                        ? [new SecurityRequirement(['BearerAuth' => []])]
                        : [];

                    $queryParameters = $this->queryParametersFor($key);
                    $replacedParameters = array_merge(
                        ['Accept', 'X-Locale'],
                        array_map(fn (Parameter $parameter) => $parameter->name, $queryParameters)
                    );
                    $operation->parameters = array_values(array_filter(
                        $operation->parameters,
                        fn ($parameter) => ! ($parameter instanceof Parameter
                            && in_array($parameter->name, $replacedParameters, true)
                            && ($parameter->in === 'header' || $parameter->in === 'query'))
                    ));
                    $operation->addParameters([
                        Parameter::make('Accept', 'header')
                            ->description('Ожидаемый формат ответа.')
                            ->example('application/json')
                            ->setSchema(Schema::fromType((new StringType)->enum(['application/json']))),
                        Parameter::make('X-Locale', 'header')
                            ->description('Язык ответа. Также поддерживаются `?lang=` и `Accept-Language`; по умолчанию `tm`.')
                            ->example('tm')
                            ->setSchema(Schema::fromType((new StringType)->enum(['tm', 'ru', 'en']))),
                    ]);

                    foreach ($details['parameters'] ?? [] as $parameter) {
                        $operation->parameters[] = $parameter;
                    }

                    foreach ($queryParameters as $parameter) {
                        $operation->parameters[] = $parameter;
                    }

                    foreach ($operation->parameters as $parameter) {
                        if ($parameter instanceof Parameter && $parameter->in === 'path' && $parameter->name === 'slug') {
                            $parameter->example(str_contains($key, 'categories/') ? 'news' : 'portal-news');
                        }
                    }

                    if (isset($details['request']) && $operation->requestBodyObject !== null) {
                        foreach (array_keys($operation->requestBodyObject->content) as $contentType) {
                            $mediaType = $operation->requestBodyObject->getContent($contentType);
                            if ($mediaType instanceof MediaType) {
                                $mediaType->setExample($details['request']);
                                $operation->requestBodyObject->addContent($contentType, $mediaType);
                            }
                        }
                    }

                    foreach ($operation->responses ?? [] as $response) {
                        if ($response instanceof Response && in_array((string) $response->code, ['200', '201'], true)) {
                            $contentType = isset($response->content['application/json'])
                                ? 'application/json'
                                : (array_key_first($response->content) ?? null);

                            if ($contentType !== null) {
                                $mediaType = $response->getMediaType($contentType);
                                if ($mediaType instanceof MediaType) {
                                    $mediaType->setExample($details['response']);
                                    $response->setContent($contentType, $mediaType);
                                }
                            }
                        }
                    }

                    foreach ($details['errors'] as $status => $description) {
                        $hasResponse = collect($operation->responses ?? [])
                            ->contains(fn ($response) => $response instanceof Response && (string) $response->code === (string) $status);

                        if (! $hasResponse) {
                            $operation->addResponse(Response::make($status)->setDescription($description));
                        }
                    }
                }
            }
        });
    }

    /**
     * @return array<string, array{
     *     summary: string,
     *     description: string,
     *     tag: string,
     *     auth: bool,
     *     response: array<string, mixed>,
     *     request?: array<string, mixed>,
     *     parameters?: array<int, Parameter>,
     *     errors: array<int, string>
     * }>
     */
    private function apiDocumentation(): array
    {
        $article = [
            'id' => 42,
            'title' => 'Новости портала',
            'excerpt' => 'Краткое описание новости.',
            'slug' => 'portal-news',
            'image' => null,
            'views' => 15,
            'is_featured' => false,
            'published_at' => '2026-01-15T09:30:00+00:00',
            'category' => ['id' => 2, 'name' => 'Новости', 'translations' => ['tm' => 'Täzelikler', 'ru' => 'Новости'], 'slug' => 'news', 'sort_order' => 1],
            'author' => ['id' => 3, 'name' => 'Редактор', 'avatar' => null],
        ];
        $user = ['id' => 3, 'name' => 'Тестовый читатель', 'phone' => '+99360000000', 'email' => null, 'avatar' => null, 'role' => 'reader'];
        $page = ['current_page' => 1, 'last_page' => 1, 'per_page' => 12, 'total' => 1, 'from' => 1, 'to' => 1, 'path' => 'http://localhost/api/v1/articles'];
        $pagination = ['current_page' => 1, 'last_page' => 1, 'per_page' => 10, 'total' => 0, 'from' => null, 'to' => null, 'path' => 'http://localhost/api/v1/articles'];

        $entry = static function (
            string $summary,
            string $description,
            string $tag,
            array $response,
            bool $auth = false,
            ?array $request = null,
            array $errors = [],
        ): array {
            return array_filter([
                'summary' => $summary,
                'description' => $description,
                'tag' => $tag,
                'auth' => $auth,
                'response' => $response,
                'request' => $request,
                'errors' => $errors,
            ], fn ($value) => $value !== null);
        };

        return [
            'get articles' => $entry('Список новостей', 'Возвращает опубликованные новости с пагинацией. Фильтры: category и tag по slug, featured=true, search по заголовку или тексту; sort принимает latest или popular. limit ограничен диапазоном 1–20, по умолчанию 12.', 'Articles', ['data' => [$article], 'meta' => $page, 'links' => ['first' => 'http://localhost/api/v1/articles?page=1', 'last' => 'http://localhost/api/v1/articles?page=1', 'prev' => null, 'next' => null]]),
            'get articles/popular' => $entry('Популярные новости', 'Возвращает до шести опубликованных новостей, отсортированных по числу просмотров.', 'Articles', ['data' => [$article]]),
            'get articles/trending' => $entry('Популярные за последнее время новости', 'Возвращает до шести опубликованных новостей с учётом избранного, одобренных комментариев за 30 дней и просмотров.', 'Articles', ['data' => [$article]]),
            'get articles/featured' => $entry('Главная новость', 'Возвращает избранную опубликованную новость, а если её нет — последнюю опубликованную. Если новостей нет, data равен null.', 'Articles', ['data' => $article]),
            'get articles/{slug}' => $entry('Получить новость', 'Возвращает опубликованную новость по slug, связанные новости, категорию, автора и теги. При запросе увеличивает счётчик просмотров.', 'Articles', ['data' => array_merge($article, ['content' => 'Полный текст новости.', 'comments_count' => 2, 'tags' => [], 'related_articles' => []])], errors: [404 => 'Опубликованная новость с таким slug не найдена.']),
            'get articles/{slug}/comments' => $entry('Комментарии к новости', 'Возвращает только одобренные комментарии, по 10 на странице.', 'Comments', ['data' => [], 'meta' => $pagination, 'links' => ['first' => 'http://localhost/api/v1/articles/portal-news/comments?page=1', 'last' => 'http://localhost/api/v1/articles/portal-news/comments?page=1', 'prev' => null, 'next' => null]], errors: [404 => 'Опубликованная новость с таким slug не найдена.']),
            'post articles/{slug}/comments' => $entry('Добавить комментарий', 'Создаёт комментарий со статусом pending для авторизованного пользователя; публикация ожидает модерации. Ограничение: 5 запросов в минуту.', 'Comments', ['message' => 'Комментарий отправлен на модерацию.', 'data' => ['id' => 10, 'body' => 'Спасибо за новость!', 'status' => 'pending', 'created_at' => '2026-01-15T09:30:00+00:00', 'user' => ['id' => 3, 'name' => 'Тестовый читатель', 'avatar' => null]]], true, ['body' => 'Спасибо за новость!'], [401 => 'Требуется Sanctum bearer token.', 404 => 'Опубликованная новость с таким slug не найдена.', 422 => 'Поле body обязательно и должно содержать не более 1000 символов.']),
            'get categories' => $entry('Список категорий', 'Возвращает категории в порядке sort_order с именем для выбранного языка и всеми переводами.', 'Categories', ['data' => [['id' => 2, 'name' => 'Новости', 'translations' => ['tm' => 'Täzelikler', 'ru' => 'Новости', 'en' => 'News'], 'slug' => 'news', 'sort_order' => 1]]]),
            'get categories/{slug}/articles' => $entry('Новости категории', 'Возвращает опубликованные новости категории по slug с пагинацией. limit ограничен 1–20, по умолчанию 12.', 'Categories', ['data' => [$article], 'meta' => $page, 'links' => ['first' => 'http://localhost/api/v1/categories/news/articles?page=1', 'last' => 'http://localhost/api/v1/categories/news/articles?page=1', 'prev' => null, 'next' => null]], errors: [404 => 'Категория с таким slug не найдена.']),
            'get categories/{slug}/popular' => $entry('Популярные новости категории', 'Возвращает опубликованные новости категории по slug, отсортированные по просмотрам, с пагинацией.', 'Categories', ['data' => [$article], 'meta' => $page, 'links' => ['first' => 'http://localhost/api/v1/categories/news/popular?page=1', 'last' => 'http://localhost/api/v1/categories/news/popular?page=1', 'prev' => null, 'next' => null]], errors: [404 => 'Категория с таким slug не найдена.']),
            'get tags' => $entry('Список тегов', 'Возвращает теги в порядке id.', 'Categories', ['data' => [['id' => 5, 'name' => 'Общество', 'slug' => 'society']]]),
            'get banners' => $entry('Активные баннеры', 'Возвращает активные баннеры.', 'Banners', ['data' => [['id' => 1, 'title' => 'Баннер портала', 'image' => null, 'link' => 'https://example.test', 'sort_order' => 1]]]),
            'post auth/send-otp' => $entry('Запросить OTP-код', 'Отправляет код подтверждения для имени. Код включён в ответ для тестирования и истекает через 10 минут; лимит — 10 запросов в минуту.', 'Auth', ['message' => 'Код подтверждения отправлен.', 'code' => '1234', 'name' => 'Тестовый читатель'], request: ['name' => 'Тестовый читатель', 'phone' => '+99360000000'], errors: [422 => 'Некорректные поля name или phone.', 429 => 'Превышен лимит запросов; повторите позже.']),
            'post auth/verify-otp' => $entry('Проверить OTP-код', 'Проверяет OTP, при необходимости создаёт читателя и возвращает Sanctum token. Лимит — 20 запросов в минуту.', 'Auth', ['message' => 'Вход выполнен.', 'token' => 'example-sanctum-token', 'user' => $user], request: ['name' => 'Тестовый читатель', 'code' => '1234', 'phone' => '+99360000000'], errors: [422 => 'Неверный или просроченный код либо некорректные поля.']),
            'post auth/login' => $entry('Вход по OTP', 'Совместимый маршрут, выполняющий проверку OTP как auth/verify-otp. Лимит — 10 запросов в минуту.', 'Auth', ['message' => 'Вход выполнен.', 'token' => 'example-sanctum-token', 'user' => $user], request: ['name' => 'Тестовый читатель', 'code' => '1234'], errors: [422 => 'Неверный или просроченный код либо некорректные поля.']),
            'post auth/register' => $entry('Регистрация по OTP', 'Совместимый маршрут, выполняющий проверку OTP и при необходимости создающий читателя. Лимит — 10 запросов в минуту.', 'Auth', ['message' => 'Вход выполнен.', 'token' => 'example-sanctum-token', 'user' => $user], request: ['name' => 'Тестовый читатель', 'code' => '1234'], errors: [422 => 'Неверный или просроченный код либо некорректные поля.']),
            'get auth/me' => $entry('Текущий пользователь', 'Возвращает пользователя, связанного с текущим Sanctum token.', 'Auth', ['data' => $user], true, errors: [401 => 'Требуется Sanctum bearer token.']),
            'post auth/logout' => $entry('Выход', 'Удаляет текущий Sanctum token.', 'Auth', ['message' => 'Выход выполнен.'], true, errors: [401 => 'Требуется Sanctum bearer token.']),
            'get me' => $entry('Профиль пользователя', 'Возвращает id, name и avatar текущего пользователя.', 'Auth', ['data' => ['id' => 3, 'name' => 'Тестовый читатель', 'avatar' => null]], true, errors: [401 => 'Требуется Sanctum bearer token.']),
            'put me' => $entry('Обновить профиль', 'Частично обновляет профиль. Поддерживаются name, email, phone, password с password_confirmation и avatar (jpg, jpeg, png, webp, до 2 МБ); avatar=null удаляет аватар.', 'Auth', ['data' => ['id' => 3, 'name' => 'Тестовый читатель', 'avatar' => null]], true, ['name' => 'Тестовый читатель'], [401 => 'Требуется Sanctum bearer token.', 422 => 'Поля не прошли валидацию.']),
        ];
    }

    /**
     * @return array<int, Parameter>
     */
    private function queryParametersFor(string $key): array
    {
        $page = Parameter::make('page', 'query')
            ->description('Номер страницы пагинации.')
            ->example(1)
            ->setSchema(Schema::fromType(new IntegerType));

        $limit = Parameter::make('limit', 'query')
            ->description('Количество элементов на странице, от 1 до 20; по умолчанию 12.')
            ->example(12)
            ->setSchema(Schema::fromType(new IntegerType));

        return match ($key) {
            'get articles' => [
                $page,
                $limit,
                Parameter::make('category', 'query')->description('Фильтр по slug категории.')->example('news')->setSchema(Schema::fromType(new StringType)),
                Parameter::make('tag', 'query')->description('Фильтр по slug тега.')->example('society')->setSchema(Schema::fromType(new StringType)),
                Parameter::make('featured', 'query')->description('Оставить только избранные новости.')->example(true)->setSchema(Schema::fromType(new BooleanType)),
                Parameter::make('search', 'query')->description('Поиск по заголовку или тексту на выбранном языке.')->example('портал')->setSchema(Schema::fromType(new StringType)),
                Parameter::make('sort', 'query')->description('Порядок сортировки: latest или popular.')->example('latest')->setSchema(Schema::fromType((new StringType)->enum(['latest', 'popular']))),
            ],
            'get articles/{slug}/comments' => [$page],
            'get categories/{slug}/articles', 'get categories/{slug}/popular' => [$page, $limit],
            default => [],
        };
    }
}
