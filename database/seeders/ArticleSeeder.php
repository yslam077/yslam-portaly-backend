<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();
        $userId = $admin ? $admin->id : 1;

        $categories = Category::all()->keyBy('slug');
        $tags = Tag::all();

        $articles = [
            // 1. Featured Article (Syýasat)
            [
                'category_slug' => 'syyasat',
                'title' => [
                    'tm' => 'TÜRKMENISTANYŇ HALKARA HYZMATDAŞLYGYNDA TÄZE DÖWÜR WE MÖHÜM ÄDIMLER',
                    'ru' => 'НОВЫЙ ЭТАП В МЕЖДУНАРОДНОМ СОТРУДНИЧЕСТВЕ ТУРКМЕНИСТАНА',
                    'en' => 'A NEW ERA IN INTERNATIONAL COOPERATION OF TURKMENISTAN',
                ],
                'slug' => 'turkmenistanyn-halkara-hyzmatdaslygynda-taze-dowur',
                'excerpt' => [
                    'tm' => 'Dünýä döwletleri bilen söwda-ykdysady we medeni gatnaşyklary ösdürmek boýunça giň gerimli maslahatlar geçirildi.',
                    'ru' => 'Проведены масштабные консультации по развитию торгово-экономических и культурных связей со странами мира.',
                    'en' => 'Large-scale consultations were held to develop trade, economic, and cultural ties with countries around the world.',
                ],
                'content' => [
                    'tm' => '<p>Dünýä döwletleri bilen söwda-ykdysady we medeni gatnaşyklary pugtalandyrmak maksady bilen halkara derejede möhüm şertnamalara gol çekildi.</p><p>Halkara derejede alnyp barylýan oňyn Bitaraplyk syýasaty sebitde parahatçylygy we durnukly ösüşi üpjün etmäge gönükdirilendir.</p>',
                    'ru' => '<p>В целях укрепления торгово-экономических и культурных связей со странами мира подписаны важные международные соглашения.</p>',
                    'en' => '<p>Important international agreements were signed to strengthen trade, economic, and cultural relations with countries worldwide.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => true,
                'views' => 5410,
                'published_at' => now()->subHours(2),
            ],

            // 2. Sport Articles
            [
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'SPORT WE SAGDYN DURMUŞ: ÝAŞ TÜRKMEN TALYPLARY ALTYN MEDAL GAZANDY',
                    'ru' => 'СПОРТ И ЗДОРОВЬЕ: МОЛОДЫЕ ТУРКМЕНСКИЕ СПОРТСМЕНЫ ЗАВОЕВАЛИ ЗОЛОТЫЕ МЕДАЛИ',
                    'en' => 'SPORT AND HEALTH: YOUNG TURKMEN ATHLETES WON GOLD MEDALS',
                ],
                'slug' => 'sport-we-sagdyn-durmus-altyn-medal',
                'excerpt' => [
                    'tm' => 'Halkara ýaryşda türkmen türgenleri üstünlikli çykyş edip, birnäçe altyn medallara mynasyp boldular.',
                    'ru' => 'Туркменские атлеты успешно выступили на международных соревнованиях, завоевав несколько золотых медалей.',
                    'en' => 'Turkmen athletes performed successfully at international competitions, winning several gold medals.',
                ],
                'content' => [
                    'tm' => '<p>Halkara derejede geçirilen sport bäsleşiklerinde ildeşlerimiz ýokary ussatlyk görkezdiler we watanymyzyň baýdagyny belende galdyrdylar.</p>',
                    'ru' => '<p>На международных спортивных соревнованиях наши соотечественники продемонстрировали высокое мастерство.</p>',
                    'en' => '<p>At international sports competitions, our compatriots showed exceptional skill.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 1240,
                'published_at' => now()->subHours(5),
            ],
            [
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'FUTBOL BOÝUNÇA ÝURT ÇEMPIONATYNDA GYZGALAŇLY DUŞUŞYKLAR DOWAM EDÝÄR',
                    'ru' => 'В ЧЕМПИОНАТЕ СТРАНЫ ПО ФУТБОЛУ ПРОДОЛЖАЮТСЯ ЗАХВАТЫВАЮЩИЕ МАТЧИ',
                    'en' => 'EXCITING MATCHES CONTINUE IN THE NATIONAL FOOTBALL CHAMPIONSHIP',
                ],
                'slug' => 'futbol-boyunca-yurt-cempionatynda-gyzgalanly-dususyklar',
                'excerpt' => [
                    'tm' => 'Möwsümiň nobatdaky tapgyrynda toparlar ýokary netijeleri gazanmak üçin meýdançada bäsleşdiler.',
                    'ru' => 'В очередном туре сезона команды боролись за высокие результаты на футбольном поле.',
                    'en' => 'In the next round of the season, teams competed fiercely on the pitch for victory.',
                ],
                'content' => [
                    'tm' => '<p>Futbol janköýerleri üçin täsirli we çekeleşikli oýunlar boldy. Ýeňijiler soňky minutlarda kesgitlendi.</p>',
                    'ru' => '<p>Для футбольных болельщиков матчи выдались зрелищными и напряженными.</p>',
                    'en' => '<p>For football fans, the games were thrilling and intense until the final whistle.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 980,
                'published_at' => now()->subHours(8),
            ],

            // 3. Biznes / Ykdysadyýet
            [
                'category_slug' => 'biznes',
                'title' => [
                    'tm' => 'TÜRKMENISTANDA TÄZE TELEKEÇILIK WE SANLY YKDYSADYÝET TASLAMALARY',
                    'ru' => 'НОВЫЕ ПРЕДПРИНИМАТЕЛЬСКИЕ И ЦИФРОВЫЕ ПРОЕКТЫ В ТУРКМЕНИСТАНЕ',
                    'en' => 'NEW ENTREPRENEURSHIP AND DIGITAL ECONOMY PROJECTS IN TURKMENISTAN',
                ],
                'slug' => 'turkmenistanda-taze-telekecilik-taslamalary',
                'excerpt' => [
                    'tm' => 'Hususy pudagy goldamak we sanlylaşdyrmak boýunça täze maýa goýum maksatnamalary yglan edildi.',
                    'ru' => 'Объявлены новые инвестиционные программы по поддержке частного сектора и цифровизации.',
                    'en' => 'New investment programs to support the private sector and digitalization were announced.',
                ],
                'content' => [
                    'tm' => '<p>Sanly ykdysadyýet ulgamynda täze startaplar we telekeçilik başlangyçlary öňe sürülýär.</p>',
                    'ru' => '<p>В сфере цифровой экономики внедряются инновационные стартапы и бизнес-инициативы.</p>',
                    'en' => '<p>In the digital economy sector, innovative startups and business initiatives are emerging.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 2315,
                'published_at' => now()->subDay(),
            ],
            [
                'category_slug' => 'ekonomika',
                'title' => [
                    'tm' => 'SENAGAT WE ENERGETIKA PUDAGYNDA EKSPORT MÜMKINÇILIKLERI GIŇELÝÄR',
                    'ru' => 'ЭКСПОРТНЫЙ ПОТЕНЦИАЛ В ПРОМЫШЛЕННОСТИ И ЭНЕРГЕТИКЕ РАСШИРЯЕТСЯ',
                    'en' => 'EXPORT POTENTIAL IN INDUSTRY AND ENERGY EXPANDS',
                ],
                'slug' => 'senagat-we-energetika-pudagynda-eksport-mumkincilikleri',
                'excerpt' => [
                    'tm' => 'Täze zawodlaryň we senagat kärhanalarynyň açylmagy ýurduň ykdysady kuwwatyny has-da berkidýär.',
                    'ru' => 'Открытие новых заводов и промышленных предприятий укрепляет экономический потенциал страны.',
                    'en' => 'Opening new factories and industrial plants strengthens the economic power of the country.',
                ],
                'content' => [
                    'tm' => '<p>Eksporta niýetlenen önümleri öndürmekde täze sepgitler gazanylýar.</p>',
                    'ru' => '<p>Достигаются новые высоты в производстве экспортоориентированной продукции.</p>',
                    'en' => '<p>New milestones are achieved in the manufacturing of export-oriented goods.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 1240,
                'published_at' => now()->subDays(2),
            ],

            // 4. Kultura / Dunya / Awtoulaglar
            [
                'category_slug' => 'kultura',
                'title' => [
                    'tm' => 'MEDENI MIRAS WE DÜNÝÄ EDEBIÝATY: AŞGABATDA DÖREDIJILIK FORUMY GEÇIRILDI',
                    'ru' => 'КУЛЬТУРНОЕ НАСЛЕДИЕ: В АШХАБАДЕ ПРОШЕЛ ТВОРЧЕСКИЙ ФОРУМ',
                    'en' => 'CULTURAL HERITAGE: CREATIVE FORUM HELD IN ASHGABAT',
                ],
                'slug' => 'medeni-miras-doredijilik-forumy-gecirildi',
                'excerpt' => [
                    'tm' => 'Milli medeniýetimizi we edebi mirasymyzy dünýä ýaýmak maksady bilen geçirilen çäre uly seslenme döretdi.',
                    'ru' => 'Мероприятие, направленное на популяризацию национальной культуры, вызвало широкий интерес.',
                    'en' => 'The event aimed at promoting national culture and literary heritage resonated widely.',
                ],
                'content' => [
                    'tm' => '<p>Şahyrlaryň, ýazyjylaryň we sungat işgärleriniň gatnaşmagynda gyzykly duşuşyklar guraldy.</p>',
                    'ru' => '<p>Прошли интересные встречи с участием поэтов, писателей и деятелей искусств.</p>',
                    'en' => '<p>Engaging meetings were held with poets, writers, and cultural figures.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 1240,
                'published_at' => now()->subDays(2),
            ],
            [
                'category_slug' => 'awtoulaglar',
                'title' => [
                    'tm' => 'ELEKTROMOBILLER WE EKOLOGIÝA TÄZE AWTOULAÝ TILSIDERI BILEN TANŞYŇ',
                    'ru' => 'ЭЛЕКТРОМОБИЛИ И ЭКОЛОГИЯ: ЗНАКОМСТВО С НОВЫМИ АВТОМОБИЛЬНЫМИ ТЕХНОЛОГИЯМИ',
                    'en' => 'ELECTRIC CARS AND ECOLOGY: DISCOVER NEW AUTOMOTIVE TECHNOLOGIES',
                ],
                'slug' => 'elektromobiller-we-ekologiya-taze-awtoulag-tilsimleri',
                'excerpt' => [
                    'tm' => 'Dünýäde elektrik togy bilen işleýän häzirki zaman awtoulaglarynyň sany günsaýyn artýar.',
                    'ru' => 'Количество современных электромобилей в мире растет с каждым днем.',
                    'en' => 'The number of modern electric vehicles in the world is growing every day.',
                ],
                'content' => [
                    'tm' => '<p>Ýaşyl energiýa we ekologik taýdan arassa awtoulag tehnologiýalary boýunça täze modeller tanyşdyryldy.</p>',
                    'ru' => '<p>Были представлены новые экологически чистые модели на зеленой энергии.</p>',
                    'en' => '<p>New green energy and eco-friendly vehicle models were presented.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 1240,
                'published_at' => now()->subDays(3),
            ],
            [
                'category_slug' => 'dunya',
                'title' => [
                    'tm' => 'DÜNÝÄ HABARLARY: HALKARA YLMY GÖZLEGLERDE TÄZE AÇYŞLAR',
                    'ru' => 'МИРОВЫЕ НОВОСТИ: НОВЫЕ ОТКРЫТИЯ В МЕЖДУНАРОДНЫХ НАУЧНЫХ ИССЛЕДОВАНИЯХ',
                    'en' => 'WORLD NEWS: NEW DISCOVERIES IN INTERNATIONAL SCIENTIFIC RESEARCH',
                ],
                'slug' => 'dunya-habarlary-halkara-ylmy-gozlegler',
                'excerpt' => [
                    'tm' => 'Alymlar tarapyndan kosmos we täze energiýa çeşmeleri boýunça möhüm barlaglar tamamlandy.',
                    'ru' => 'Ученые завершили важные исследования в области космоса и новых источников энергии.',
                    'en' => 'Scientists have completed important studies on space and new energy sources.',
                ],
                'content' => [
                    'tm' => '<p>Dünýä ylmy jemgyýetçiliginiň ünsüni çeken täze açyşlar geljekki ösüş üçin möhüm ähmiýete eýedir.</p>',
                    'ru' => '<p>Новые открытия мирового научного сообщества имеют важное значение для будущего развития.</p>',
                    'en' => '<p>New discoveries attracting the global scientific community are crucial for future growth.</p>',
                ],
                'image' => null,
                'status' => 'published',
                'is_featured' => false,
                'views' => 1240,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($articles as $art) {
            $category = $categories[$art['category_slug']] ?? Category::first();

            $article = Article::updateOrCreate(
                ['slug' => $art['slug']],
                [
                    'category_id' => $category->id,
                    'user_id' => $userId,
                    'title' => $art['title'],
                    'excerpt' => $art['excerpt'],
                    'content' => $art['content'],
                    'image' => $art['image'],
                    'status' => $art['status'],
                    'is_featured' => $art['is_featured'],
                    'views' => $art['views'],
                    'published_at' => $art['published_at'],
                ]
            );

            // Attach random tags
            if ($tags->isNotEmpty()) {
                $article->tags()->sync($tags->random(min(3, $tags->count()))->pluck('id')->toArray());
            }
        }
    }
}
