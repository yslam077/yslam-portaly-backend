<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->value('id');

        if ($adminId === null) {
            throw new RuntimeException('An admin user must be seeded before articles.');
        }

        $categories = Category::all()->keyBy('slug');
        $tags = Tag::all()->keyBy('slug');
        $demoLabels = ['tm' => '[DEMO]', 'ru' => '[ДЕМО]', 'en' => '[DEMO]'];
        $demoNotices = [
            'tm' => 'Bu mysal ýazgy diňe portalyň dizaýnyny we maglumat akymyny barlamak üçin döredildi. Onda hakyky wakalar ýa-da tassyklanan habarlar beýan edilmeýär.',
            'ru' => 'Этот вымышленный материал создан только для проверки интерфейса и демонстрации данных. Он не описывает реальные события и не является новостью.',
            'en' => 'This fictional sample is provided only to preview the interface and data flow. It does not describe real events and is not factual news.',
        ];

        $articles = [
            [
                'slug' => 'turkmenistanyn-halkara-hyzmatdaslygynda-taze-dowur',
                'category_slug' => 'syyasat',
                'title' => [
                    'tm' => 'Halkara hyzmatdaşlygy öwrenmek üçin açyk dialogyň ähmiýeti',
                    'ru' => 'Значение открытого диалога для международного сотрудничества',
                    'en' => 'The value of open dialogue in international cooperation',
                ],
                'excerpt' => [
                    'tm' => 'Mysal makalada döwletleriň arasyndaky dialogyň ykdysady we medeni gatnaşyklary öwrenmäge nähili kömek edýändigi beýan edilýär.',
                    'ru' => 'В демонстрационном материале рассматривается, как диалог помогает изучать экономические и культурные связи между странами.',
                    'en' => 'This sample looks at how dialogue can help explore economic and cultural ties between countries.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['syyasat', 'turkmenistan', 'tazelikler'],
                'is_featured' => true,
            ],
            [
                'slug' => 'sport-we-sagdyn-durmus-altyn-medal',
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'Sportda taýýarlyk we toparlaýyn ruhuň ähmiýeti',
                    'ru' => 'Подготовка и командный дух в спорте',
                    'en' => 'Preparation and team spirit in sport',
                ],
                'excerpt' => [
                    'tm' => 'Türgenleşik, özara goldaw we maksada yzygiderli ymtylmak sport tejribesiniň möhüm bölekleridir.',
                    'ru' => 'Тренировки, взаимная поддержка и последовательное стремление к цели важны в спортивной практике.',
                    'en' => 'Training, mutual support and steady effort are important parts of sporting practice.',
                ],
                'image' => 'articles/football-team-2015.jpg',
                'tags' => ['futbol', 'saglyk', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'futbol-boyunca-yurt-cempionatynda-gyzgalanly-dususyklar',
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'Futbol möwsümine tomaşa etmegiň gysgaça ýolbeledi',
                    'ru' => 'Краткий путеводитель для зрителей футбольного сезона',
                    'en' => 'A short guide for following a football season',
                ],
                'excerpt' => [
                    'tm' => 'Oýun düzgünleri, toparlaryň taýýarlygy we janköýer medeniýeti barada täze başlanlar üçin syn.',
                    'ru' => 'Обзор правил игры, подготовки команд и культуры болельщиков для начинающих зрителей.',
                    'en' => 'An introduction to the rules, team preparation and supporter culture for new viewers.',
                ],
                'image' => 'articles/football-team-2015.jpg',
                'tags' => ['futbol', 'ylym', 'tazelikler'],
                'is_featured' => false,
            ],
            [
                'slug' => 'turkmenistanda-taze-telekecilik-taslamalary',
                'category_slug' => 'biznes',
                'title' => [
                    'tm' => 'Kiçi telekeçilikde sanly gurallaryň mümkinçilikleri',
                    'ru' => 'Возможности цифровых инструментов для малого бизнеса',
                    'en' => 'Digital tools and opportunities for small businesses',
                ],
                'excerpt' => [
                    'tm' => 'Onlaýn hyzmatlar, elektron tölegler we maglumat seljermesi telekeçilik işini guramagyň täze usullaryny hödürleýär.',
                    'ru' => 'Онлайн-сервисы, электронные платежи и анализ данных предлагают новые способы организации бизнеса.',
                    'en' => 'Online services, digital payments and data analysis offer new ways to organize a business.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['biznes', 'tehnologiya', 'ekonomika'],
                'is_featured' => false,
            ],
            [
                'slug' => 'senagat-we-energetika-pudagynda-eksport-mumkincilikleri',
                'category_slug' => 'ekonomika',
                'title' => [
                    'tm' => 'Energiýa netijeliligi: gündelik çözgütlerden uly ulgamlara çenli',
                    'ru' => 'Энергоэффективность: от повседневных решений до крупных систем',
                    'en' => 'Energy efficiency, from everyday choices to larger systems',
                ],
                'excerpt' => [
                    'tm' => 'Energiýany rejeli peýdalanmak we serişdeleri tygşytlamak boýunça umumy çemeleşmeler.',
                    'ru' => 'Общие подходы к рациональному использованию энергии и экономии ресурсов.',
                    'en' => 'An overview of practical approaches to using energy and resources more efficiently.',
                ],
                'image' => 'articles/darvaza.jpg',
                'tags' => ['ekonomika', 'energetika', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'medeni-miras-doredijilik-forumy-gecirildi',
                'category_slug' => 'kultura',
                'title' => [
                    'tm' => 'Türkmen haly nagyşlary: sungat we taryhyň umumy dili',
                    'ru' => 'Туркменский ковёр: общий язык искусства и истории',
                    'en' => 'Turkmen carpet motifs, where art meets history',
                ],
                'excerpt' => [
                    'tm' => 'Halyçylyk däpleri, nagyşlaryň manylary we olaryň häzirki medeniýetdäki orny barada syn.',
                    'ru' => 'Обзор ковровых традиций, значений орнаментов и их места в современной культуре.',
                    'en' => 'A look at carpet-making traditions, the meaning of motifs and their place in contemporary culture.',
                ],
                'image' => 'articles/turkmen-carpet.jpg',
                'tags' => ['kultura', 'medeni-miras', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'elektromobiller-we-ekologiya-taze-awtoulag-tilsimleri',
                'category_slug' => 'awtoulaglar',
                'title' => [
                    'tm' => 'Şäherde ulag saýlamakda energiýa tygşytlylygy',
                    'ru' => 'Энергоэффективность при выборе городского транспорта',
                    'en' => 'Energy efficiency when choosing city transport',
                ],
                'excerpt' => [
                    'tm' => 'Ulagyň görnüşini saýlanyňda ýol şertlerini, hyzmat çykdajylaryny we daşky gurşawa täsirini deňeşdirmek peýdalydyr.',
                    'ru' => 'При выборе транспорта полезно учитывать дорожные условия, расходы на обслуживание и влияние на окружающую среду.',
                    'en' => 'When choosing a vehicle, it helps to consider road conditions, maintenance costs and environmental impact.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['awtoulaglar', 'tehnologiya', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'dunya-habarlary-halkara-ylmy-gozlegler',
                'category_slug' => 'dunya',
                'title' => [
                    'tm' => 'Kosmosdan syn etmek Ýeri öwrenmäge nähili kömek edýär',
                    'ru' => 'Как наблюдения из космоса помогают изучать Землю',
                    'en' => 'How observations from space help us study Earth',
                ],
                'excerpt' => [
                    'tm' => 'Hemralardan alnan şekiller howa, suw we ýer örtügi baradaky maglumatlary seljermäge mümkinçilik berýär.',
                    'ru' => 'Спутниковые снимки помогают анализировать данные о погоде, воде и земном покрове.',
                    'en' => 'Satellite imagery helps researchers analyze weather, water and land-cover information.',
                ],
                'image' => 'articles/dust-storm.jpg',
                'tags' => ['ylym', 'tehnologiya', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'ashgabatda-jemgyyetcilik-meydanlary',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Şäher giňişlikleri hemmeler üçin amatly bolanda',
                    'ru' => 'Когда городские пространства удобны для всех',
                    'en' => 'Making public spaces work for everyone',
                ],
                'excerpt' => [
                    'tm' => 'Ýörelgeler, dynç alyş meýdançalary we oýlanyşykly ugrukdyryş şäher durmuşynyň amatlylygyny artdyrýar.',
                    'ru' => 'Тротуары, места отдыха и понятная навигация делают городскую среду комфортнее.',
                    'en' => 'Walkways, places to rest and clear wayfinding can make urban life more comfortable.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['jemgyyet', 'turkmenistan', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'saher-baglary-we-jemgyyet-durmusy',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Şäher baglary jemgyýetçilik durmuşyna nähili goşant goşýar',
                    'ru' => 'Как городские парки дополняют общественную жизнь',
                    'en' => 'How city parks contribute to community life',
                ],
                'excerpt' => [
                    'tm' => 'Baglar dynç almak, pyýada gezmek we daşky gurşawy synlamak üçin umumy giňişlik döredýär.',
                    'ru' => 'Парки создают общее пространство для отдыха, прогулок и наблюдения за природой.',
                    'en' => 'Parks provide shared places for rest, walking and enjoying the natural environment.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['jemgyyet', 'tebigat', 'saglyk'],
                'is_featured' => false,
            ],
            [
                'slug' => 'mekdep-kitaphanalary-okuw-medeniýeti',
                'category_slug' => 'bilim',
                'title' => [
                    'tm' => 'Mekdep kitaphanalarynyň okuw medeniýetindäki orny',
                    'ru' => 'Роль школьных библиотек в культуре обучения',
                    'en' => 'The role of school libraries in learning culture',
                ],
                'excerpt' => [
                    'tm' => 'Kitaphana okuwçylara çeşmeleri tapmaga, okaýyş endiklerini ösdürmäge we özbaşdak öwrenmäge ýardam edýär.',
                    'ru' => 'Библиотека помогает школьникам находить материалы, развивать привычку к чтению и учиться самостоятельно.',
                    'en' => 'A library helps students find resources, build reading habits and learn independently.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['bilim', 'ylym', 'jemgyyet'],
                'is_featured' => false,
            ],
            [
                'slug' => 'okuwda-sanly-cesmeleri-peydalanmak',
                'category_slug' => 'bilim',
                'title' => [
                    'tm' => 'Okuw prosesinde sanly çeşmeleri peýdalanmak',
                    'ru' => 'Использование цифровых ресурсов в обучении',
                    'en' => 'Using digital resources in learning',
                ],
                'excerpt' => [
                    'tm' => 'Elektron kitaplar we bilim platformalary okuw materiallaryny gözlemegiň goşmaça ýollaryny açýar.',
                    'ru' => 'Электронные книги и образовательные платформы дают дополнительные способы находить учебные материалы.',
                    'en' => 'E-books and learning platforms offer additional ways to discover study materials.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['bilim', 'tehnologiya', 'ylym'],
                'is_featured' => false,
            ],
            [
                'slug' => 'ulanyjy-ucin-amatly-sanly-cogutler',
                'category_slug' => 'tehnologiya',
                'title' => [
                    'tm' => 'Sanly hyzmatlary hemmeler üçin düşnükli taslamak',
                    'ru' => 'Как создавать понятные цифровые сервисы',
                    'en' => 'Designing digital services that are easy to use',
                ],
                'excerpt' => [
                    'tm' => 'Düşnükli sözler, ýönekeý ädimler we ykjam enjamlara laýyk görnüş sanly hyzmatlaryň ulanylyşyny ýeňilleşdirýär.',
                    'ru' => 'Понятные формулировки, простые шаги и поддержка мобильных устройств упрощают работу с цифровыми сервисами.',
                    'en' => 'Clear language, simple steps and mobile-friendly layouts make digital services easier to use.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['tehnologiya', 'jemgyyet', 'biznes'],
                'is_featured' => false,
            ],
            [
                'slug' => 'maglumat-howpsuzlygy-gundelik-endikler',
                'category_slug' => 'tehnologiya',
                'title' => [
                    'tm' => 'Maglumat howpsuzlygy üçin gündelik sanly endikler',
                    'ru' => 'Повседневные цифровые привычки для защиты данных',
                    'en' => 'Everyday digital habits that help protect information',
                ],
                'excerpt' => [
                    'tm' => 'Üýtgeşik parollar, täzelenen programma üpjünçiligi we seresaply baglanyşyk barlagy şahsy maglumatlary goramaga kömek edýär.',
                    'ru' => 'Уникальные пароли, обновления программ и проверка ссылок помогают защищать личные данные.',
                    'en' => 'Unique passwords, software updates and careful link checks help protect personal information.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['tehnologiya', 'saglyk', 'biznes'],
                'is_featured' => false,
            ],
            [
                'slug' => 'muzeýler-taryhy-gurrun-beryan-ginislikler',
                'category_slug' => 'kultura',
                'title' => [
                    'tm' => 'Muzeýler: taryhy gürrüň berýän giňişlikler',
                    'ru' => 'Музеи — пространства, рассказывающие историю',
                    'en' => 'Museums, places where history is told',
                ],
                'excerpt' => [
                    'tm' => 'Kolleksiýalar we düşündirişler geçmiş bilen şu günüň arasyndaky baglanyşygy görkezmäge ýardam edýär.',
                    'ru' => 'Коллекции и пояснения помогают увидеть связь между прошлым и настоящим.',
                    'en' => 'Collections and interpretation help visitors see connections between past and present.',
                ],
                'image' => 'articles/turkmen-carpet.jpg',
                'tags' => ['kultura', 'medeni-miras', 'bilim'],
                'is_featured' => false,
            ],
            [
                'slug' => 'milli-nagyslar-hazirki-dizaynda',
                'category_slug' => 'kultura',
                'title' => [
                    'tm' => 'Milli nagyşlaryň häzirki dizaýndaky ulanylyşy',
                    'ru' => 'Национальные орнаменты в современном дизайне',
                    'en' => 'Traditional motifs in contemporary design',
                ],
                'excerpt' => [
                    'tm' => 'Däp bolan nagyşlar häzirki şekillendirişde täze reňkler we materiallar bilen utgaşyp biler.',
                    'ru' => 'Традиционные орнаменты могут сочетаться с новыми цветами и материалами в современном оформлении.',
                    'en' => 'Traditional motifs can be paired with new colors and materials in contemporary design.',
                ],
                'image' => 'articles/turkmen-carpet.jpg',
                'tags' => ['kultura', 'medeni-miras', 'tehnologiya'],
                'is_featured' => false,
            ],
            [
                'slug' => 'konurgenje-taryhy-syyahat',
                'category_slug' => 'syahatcylyk',
                'title' => [
                    'tm' => 'Köneürgenç: taryhy ýadygärliklere syýahat',
                    'ru' => 'Куня-Ургенч: знакомство с историческими памятниками',
                    'en' => 'Kunya-Urgench and its historic monuments',
                ],
                'excerpt' => [
                    'tm' => 'Taryhy ýerlere gezelenç etmezden öň ugurlary, ýerli düzgünleri we maglumat çeşmelerini öwrenmek maslahat berilýär.',
                    'ru' => 'Перед поездкой к историческим местам полезно изучить маршрут, местные правила и справочные материалы.',
                    'en' => 'Before visiting historic sites, it is useful to check routes, local guidance and visitor information.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['syahatcylyk', 'medeni-miras', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'turkmenbasy-hazar-kenary',
                'category_slug' => 'syahatcylyk',
                'title' => [
                    'tm' => 'Türkmenbaşy we Hazar deňziniň kenarýaka tebigaty',
                    'ru' => 'Туркменбаши и природа побережья Каспийского моря',
                    'en' => 'Turkmenbashi and the natural landscape of the Caspian coast',
                ],
                'excerpt' => [
                    'tm' => 'Kenarýaka syýahatyny meýilleşdireniňde howa maglumatyny, tebigaty goramak düzgünlerini we ulag mümkinçiliklerini göz öňünde tutuň.',
                    'ru' => 'Планируя поездку к морю, учитывайте погоду, правила охраны природы и варианты транспорта.',
                    'en' => 'When planning a coastal trip, consider the weather, conservation guidance and transport options.',
                ],
                'image' => 'articles/caspian-sea.jpg',
                'tags' => ['syahatcylyk', 'tebigat', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'karakum-colunin-tebigy-ayratynlyklary',
                'category_slug' => 'syahatcylyk',
                'title' => [
                    'tm' => 'Karakum çölüniň tebigy aýratynlyklary',
                    'ru' => 'Природные особенности пустыни Каракумы',
                    'en' => 'Natural features of the Karakum Desert',
                ],
                'excerpt' => [
                    'tm' => 'Çöl sebitleriniň tebigatyny öwrenmekde howpsuzlyk, suw üpjünçiligi we ýol taýýarlygy möhüm ähmiýete eýedir.',
                    'ru' => 'При изучении пустынных территорий важны безопасность, запас воды и подготовка маршрута.',
                    'en' => 'Safety, water supplies and route planning are essential when exploring desert regions.',
                ],
                'image' => 'articles/darvaza.jpg',
                'tags' => ['syahatcylyk', 'tebigat', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'oba-hojalygynda-suw-tygsytylylygy',
                'category_slug' => 'ekonomika',
                'title' => [
                    'tm' => 'Oba hojalygynda suw serişdelerini rejeli peýdalanmak',
                    'ru' => 'Рациональное использование воды в сельском хозяйстве',
                    'en' => 'Using water resources carefully in agriculture',
                ],
                'excerpt' => [
                    'tm' => 'Suwaryş usullaryny we topragyň ýagdaýyny öwrenmek serişdeleri netijeli dolandyrmaga ýardam edip biler.',
                    'ru' => 'Изучение способов полива и состояния почвы может помочь эффективнее управлять ресурсами.',
                    'en' => 'Understanding irrigation methods and soil conditions can support more efficient resource management.',
                ],
                'image' => 'articles/dust-storm.jpg',
                'tags' => ['ekonomika', 'suw', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'howa-sertlerine-tayyarlyk',
                'category_slug' => 'tehnologiya',
                'title' => [
                    'tm' => 'Howa şertlerine taýýarlykda ylmy maglumatlaryň ähmiýeti',
                    'ru' => 'Значение научных данных при подготовке к погодным условиям',
                    'en' => 'Why scientific data matters when preparing for weather',
                ],
                'excerpt' => [
                    'tm' => 'Hemra suratlary we howa gözegçilikleri daşky gurşawdaky üýtgeşmeleri düşündirmäge kömek edýän çeşmelerdir.',
                    'ru' => 'Спутниковые снимки и наблюдения за погодой помогают объяснять изменения окружающей среды.',
                    'en' => 'Satellite images and weather observations are among the tools used to understand environmental change.',
                ],
                'image' => 'articles/dust-storm.jpg',
                'tags' => ['ylym', 'tehnologiya', 'tebigat'],
                'is_featured' => false,
            ],
            [
                'slug' => 'pyyada-yurmenin-saglyga-peydasy',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Gündelik pyýada ýörişleriň saglyga peýdasy',
                    'ru' => 'Польза ежедневных прогулок пешком',
                    'en' => 'The benefits of making walking part of everyday life',
                ],
                'excerpt' => [
                    'tm' => 'Yzygiderli hereket etmek, dynç alyş we lukman maslahatlary sagdyn endikleri kemala getirmäge ýardam edýär.',
                    'ru' => 'Регулярная активность, отдых и рекомендации специалистов помогают формировать здоровые привычки.',
                    'en' => 'Regular activity, rest and professional guidance can help build healthy habits.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['saglyk', 'jemgyyet', 'sport'],
                'is_featured' => false,
            ],
            [
                'slug' => 'degramly-iymitlenis-endikleri',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Deňagramly iýmitleniş endiklerini kemala getirmek',
                    'ru' => 'Как формировать сбалансированные привычки питания',
                    'en' => 'Building balanced eating habits',
                ],
                'excerpt' => [
                    'tm' => 'Gündelik menýuda dürli önümleri we ýeterlik suw içmegi göz öňünde tutmak umumy abadançylyga peýdalydyr.',
                    'ru' => 'Разнообразие продуктов и достаточное количество воды в ежедневном рационе поддерживают общее благополучие.',
                    'en' => 'A varied menu and enough water can support everyday well-being.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['saglyk', 'jemgyyet', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'yaslar-ucin-sport-meydanlary',
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'Ýaşlar üçin sport meýdançalarynyň ähmiýeti',
                    'ru' => 'Значение спортивных площадок для молодежи',
                    'en' => 'Why sports facilities matter for young people',
                ],
                'excerpt' => [
                    'tm' => 'Elýeterli türgenleşik ýerleri ýaşlara dürli hereket görnüşlerini synap görmäge mümkinçilik berýär.',
                    'ru' => 'Доступные площадки дают молодым людям возможность заниматься разными видами активности.',
                    'en' => 'Accessible facilities give young people a place to try different forms of physical activity.',
                ],
                'image' => 'articles/olympic-complex.jpg',
                'tags' => ['sport', 'saglyk', 'jemgyyet'],
                'is_featured' => false,
            ],
            [
                'slug' => 'futbol-taryhy-we-jankoyer-medeniýeti',
                'category_slug' => 'sport',
                'title' => [
                    'tm' => 'Futbol taryhy we janköýer medeniýeti',
                    'ru' => 'История футбола и культура болельщиков',
                    'en' => 'Football history and supporter culture',
                ],
                'excerpt' => [
                    'tm' => 'Futbolyň taryhy, toparlaryň däpleri we janköýerleriň oýun günündäki tejribesi barada maglumat.',
                    'ru' => 'Материал об истории футбола, традициях команд и опыте болельщиков в дни матчей.',
                    'en' => 'A look at football history, team traditions and the match-day experience of supporters.',
                ],
                'image' => 'articles/football-team-2015.jpg',
                'tags' => ['futbol', 'medeni-miras', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'hyzmat-hilini-osdurmek',
                'category_slug' => 'biznes',
                'title' => [
                    'tm' => 'Telekeçilikde hyzmat hilini ösdürmegiň ýollary',
                    'ru' => 'Как развивать качество сервиса в бизнесе',
                    'en' => 'Ways to improve service quality in business',
                ],
                'excerpt' => [
                    'tm' => 'Müşderiniň pikirini diňlemek we hyzmatyň ädimlerini yzygiderli gözden geçirmek iş proseslerini kämilleşdirýär.',
                    'ru' => 'Обратная связь клиентов и регулярный пересмотр процессов помогают улучшать сервис.',
                    'en' => 'Customer feedback and regular process reviews can help improve a service.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['biznes', 'ekonomika', 'jemgyyet'],
                'is_featured' => false,
            ],
            [
                'slug' => 'demir-yol-sebitara-sowda',
                'category_slug' => 'ekonomika',
                'title' => [
                    'tm' => 'Demir ýol ulagynyň sebitara söwdadaky orny',
                    'ru' => 'Роль железнодорожного транспорта в региональной торговле',
                    'en' => 'Rail transport and regional trade',
                ],
                'excerpt' => [
                    'tm' => 'Ýük daşamak ugurlaryny öwrenmek söwda zynjyrlarynyň we ulag görnüşleriniň özara baglanyşygyny görkezýär.',
                    'ru' => 'Изучение грузовых маршрутов показывает, как связаны торговые цепочки и виды транспорта.',
                    'en' => 'Looking at freight routes shows how trade networks connect with different forms of transport.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['ekonomika', 'biznes', 'turkmenistan'],
                'is_featured' => false,
            ],
            [
                'slug' => 'howpsuz-yol-hereketi',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Howpsuz ýol hereketi üçin umumy düzgünler',
                    'ru' => 'Основные правила безопасного дорожного движения',
                    'en' => 'Everyday principles for safer road use',
                ],
                'excerpt' => [
                    'tm' => 'Ýol belgilerine üns bermek, tizligi göz öňünde tutmak we pyýadalar bilen hormat goýup gatnaşyk etmek möhümdir.',
                    'ru' => 'Важно соблюдать знаки, учитывать скорость и уважительно относиться к пешеходам.',
                    'en' => 'Paying attention to signs, speed and pedestrians is an important part of road safety.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['jemgyyet', 'awtoulaglar', 'saglyk'],
                'is_featured' => false,
            ],
            [
                'slug' => 'telekecilikde-taze-tehnologiyalar',
                'category_slug' => 'tehnologiya',
                'title' => [
                    'tm' => 'Täze tehnologiýalaryň telekeçilikdäki amaly orny',
                    'ru' => 'Практическое применение новых технологий в бизнесе',
                    'en' => 'Practical uses of new technologies in business',
                ],
                'excerpt' => [
                    'tm' => 'Sanly hasapçylyk, onlaýn söwda we aragatnaşyk gurallary kiçi kärhanalara işini tertipleşdirmäge kömek edip biler.',
                    'ru' => 'Цифровой учет, онлайн-торговля и коммуникационные инструменты могут помочь малым компаниям организовать работу.',
                    'en' => 'Digital bookkeeping, online sales and communication tools can help small firms organize their work.',
                ],
                'image' => 'articles/ashgabat-airport.jpg',
                'tags' => ['tehnologiya', 'biznes', 'ekonomika'],
                'is_featured' => false,
            ],
            [
                'slug' => 'dilleri-owrenmek-gundelik-tejribe',
                'category_slug' => 'bilim',
                'title' => [
                    'tm' => 'Dilleri öwrenmek üçin gündelik tejribe',
                    'ru' => 'Повседневная практика в изучении языков',
                    'en' => 'Everyday practice for language learning',
                ],
                'excerpt' => [
                    'tm' => 'Gysga okalyşlar, diňleme we täze sözleri gaýtalamak dil öwreniş endiklerini ösdürmäge kömek edýär.',
                    'ru' => 'Короткие тексты, аудирование и повторение новых слов помогают развивать языковые навыки.',
                    'en' => 'Short readings, listening and vocabulary review can support language-learning routines.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['bilim', 'ylym', 'jemgyyet'],
                'is_featured' => false,
            ],
            [
                'slug' => 'haly-nagyslarynda-geometriya',
                'category_slug' => 'kultura',
                'title' => [
                    'tm' => 'Haly nagyşlarynda geometriýa we gaýtalanýan şekiller',
                    'ru' => 'Геометрия и повторяющиеся формы в ковровых узорах',
                    'en' => 'Geometry and repeating forms in carpet patterns',
                ],
                'excerpt' => [
                    'tm' => 'Nagyşlaryň ölçegini we gaýtalanşyny synlamak dokma sungatyndaky gurluşy has gowy düşünmäge ýardam edýär.',
                    'ru' => 'Наблюдение за размером и повторением орнаментов помогает лучше понять структуру текстильного искусства.',
                    'en' => 'Looking at scale and repetition in motifs can reveal the structure of textile art.',
                ],
                'image' => 'articles/turkmen-carpet.jpg',
                'tags' => ['kultura', 'medeni-miras', 'ylym'],
                'is_featured' => false,
            ],
            [
                'slug' => 'ashgabat-saparyny-meyillesdirmek',
                'category_slug' => 'syahatcylyk',
                'title' => [
                    'tm' => 'Aşgabat şäherine ilkinji sapary meýilleşdirmek',
                    'ru' => 'Как спланировать первую поездку в Ашхабад',
                    'en' => 'Planning a first visit to Ashgabat',
                ],
                'excerpt' => [
                    'tm' => 'Sapardan öň ulag, ýaşaýyş ýeri, ýerli düzgünler we görmäge mynasyp ýerler barada maglumat ýygnamak peýdalydyr.',
                    'ru' => 'Перед поездкой полезно узнать о транспорте, размещении, местных правилах и достопримечательностях.',
                    'en' => 'Before travelling, it helps to check transport, accommodation, local guidance and places to visit.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['syahatcylyk', 'turkmenistan', 'jemgyyet'],
                'is_featured' => false,
            ],
            [
                'slug' => 'lukmancilik-ylmynda-maglumatlar',
                'category_slug' => 'tehnologiya',
                'title' => [
                    'tm' => 'Lukmançylyk ylmynda maglumatlaryň jogapkärli ulanylyşy',
                    'ru' => 'Ответственное использование данных в медицинской науке',
                    'en' => 'Responsible use of data in medical research',
                ],
                'excerpt' => [
                    'tm' => 'Ylmy maglumatlary seljermekde şahsy durmuşy goramak we netijeleri hünärmenleriň düşündirmegi wajypdyr.',
                    'ru' => 'При анализе научных данных важны защита частной жизни и профессиональная интерпретация результатов.',
                    'en' => 'Privacy protection and expert interpretation are important when analyzing research data.',
                ],
                'image' => 'articles/school-volleyball.jpg',
                'tags' => ['ylym', 'saglyk', 'tehnologiya'],
                'is_featured' => false,
            ],
            [
                'slug' => 'el-yeterli-saher-gurshawy',
                'category_slug' => 'jemgyyet',
                'title' => [
                    'tm' => 'Şäher gurşawyny hemmeler üçin elýeterli etmek',
                    'ru' => 'Доступная городская среда для всех',
                    'en' => 'Making urban environments accessible to everyone',
                ],
                'excerpt' => [
                    'tm' => 'Ýörelgeleriň, ulag duralgalarynyň we jemgyýetçilik hyzmatlarynyň elýeterliligine üns bermek gündelik hereketi ýeňilleşdirýär.',
                    'ru' => 'Доступные тротуары, остановки и общественные услуги упрощают повседневное передвижение.',
                    'en' => 'Accessible walkways, transit stops and public services make everyday movement easier.',
                ],
                'image' => 'articles/ashgabat-south.jpg',
                'tags' => ['jemgyyet', 'turkmenistan', 'saglyk'],
                'is_featured' => false,
            ],
            [
                'slug' => 'halkara-ylmy-hyzmatdaslyk',
                'category_slug' => 'dunya',
                'title' => [
                    'tm' => 'Halkara ylmy hyzmatdaşlykda maglumat paýlaşmagyň peýdasy',
                    'ru' => 'Польза обмена знаниями в международной науке',
                    'en' => 'The value of sharing knowledge in international science',
                ],
                'excerpt' => [
                    'tm' => 'Açyk maglumatlar we ylmy hyzmatdaşlyk dürli ugurlardaky gözlegleriň netijelerini deňeşdirmäge mümkinçilik berýär.',
                    'ru' => 'Открытые данные и научное сотрудничество помогают сопоставлять результаты исследований в разных областях.',
                    'en' => 'Open data and research partnerships help compare findings across different fields.',
                ],
                'image' => 'articles/dust-storm.jpg',
                'tags' => ['ylym', 'turkmenistan', 'tazelikler'],
                'is_featured' => false,
            ],
        ];

        foreach ($articles as $index => $articleData) {
            $category = $categories->get($articleData['category_slug']);

            if (! $category instanceof Category) {
                throw new RuntimeException("Unknown article category: {$articleData['category_slug']}");
            }

            $title = [];
            $excerpt = [];

            foreach ($demoLabels as $locale => $label) {
                $title[$locale] = $label.' '.$articleData['title'][$locale];
                $excerpt[$locale] = $label.' '.$articleData['excerpt'][$locale];
            }

            $content = [];

            foreach ($demoNotices as $locale => $notice) {
                $content[$locale] = '<p>'.e($excerpt[$locale]).'</p><p>'.e($notice).'</p>';
            }

            $tagIds = collect($articleData['tags'])->map(function (string $tagSlug) use ($tags): int {
                $tag = $tags->get($tagSlug);

                if (! $tag instanceof Tag) {
                    throw new RuntimeException("Unknown article tag: {$tagSlug}");
                }

                return $tag->id;
            });

            $article = Article::updateOrCreate(
                ['slug' => $articleData['slug']],
                [
                    'category_id' => $category->id,
                    'user_id' => $adminId,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'image' => $articleData['image'],
                    'status' => 'published',
                    'is_featured' => $articleData['is_featured'],
                    'views' => 500 + ($index * 137),
                    'published_at' => now()->subMinutes(($index + 1) * 47),
                ]
            );

            $article->tags()->sync($tagIds->all());
        }
    }
}
