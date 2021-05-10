<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internal\AffiliateNetwork;
use DB;

class AffiliateNetworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //truncate affiliate_network table
        DB::table('affiliate_networks')->truncate();

        $network_list = [
            "10BET",
            "138.com",
            "1and1",
            "23andMe: Referral",
            "24Option",
            "2Performant",
            "3DOcean",
            "4finance",
            "888 All Traffic",
            "888Affiliates",
            "A1Supplements",
            "A2 Hosting",
            "A4D",
            "A8",
            "ABBYY",
            "ASO",
            "ATFX",
            "AWEmpire",
            "AWIN",
            "AWeber",
            "AYI",
            "Accestrade",
            "ActionPay",
            "ActiveCampaign",
            "Ad Communal.com",
            "Ad Traction",
            "AdKnowledge",
            "AdKnowledge - ADK",
            "AdPump",
            "AdReady",
            "AdService",
            "AdSurge",
            "AdValiant",
            "Adcell",
            "Addiliate",
            "Adjust",
            "Admiral Markets",
            "AdmitAd",
            "Adperio",
            "Adrecords",
            "Adsimilis",
            "Advidi",
            "Adviralmedia",
            "AdvoCare",
            "Adyll",
            "Aff Biz",
            "AffOcean",
            "Affiliago",
            "Affiliate 4 You",
            "Affiliate Crew",
            "Affiliate Fuel",
            "Affiliate Groove",
            "Affiliate Pro",
            "Affiliate Traction",
            "AffiliateBot",
            "AffiliateFuture",
            "AffiliateWiz",
            "Affiliator",
            "Affilired",
            "Affonix",
            "Afilio",
            "Afinia",
            "Agoda",
            "Airbnb Associates",
            "Airbnb: Host",
            "Airbnb: Referral",
            "Airturn Affiliate",
            "AliExpress",
            "Alibaba",
            "Alidropship",
            "Amazon",
            "Ambassador",
            "American Standard",
            "Americanas",
            "Amped Media",
            "Anastasia",
            "Appcast",
            "Apple Music",
            "Arabclicks",
            "ArabyAds",
            "Art.com",
            "Arvixe",
            "As Seen on TV Network",
            "AsSeenOnPC",
            "Astrinsic AdQuotient",
            "Atlantic Coast Brands",
            "Atlantic Training",
            "Atrapalo - Colombia",
            "Audio Jungle",
            "Auto1",
            "Avangate",
            "AvantLink",
            "Avid Life Media",
            "Avocado Mattress Referral",
            "Avon",
            "AxiTrader",
            "AzoogleAds",
            "BTR",
            "BankrateCreditCards",
            "Bathmate",
            "BeNaughty.com",
            "Beachbody",
            "Beachbody Coach",
            "Become",
            "Becoquin",
            "Beeleads/Adclick",
            "BeenVerified.com",
            "Belboon",
            "Bet on Aces",
            "Bet-at-home",
            "Bet365",
            "Bet9ja",
            "BetAmerica",
            "BetVictor",
            "Betclic",
            "Betfair",
            "Betmaster",
            "Betsson",
            "Betway",
            "Big Bang Ads",
            "Big Fish Games",
            "BigRock",
            "Binary",
            "Bitdefender",
            "Blackstone Futures",
            "BlueHost IN",
            "Bluehost",
            "Bomnegocio",
            "Booking - Label",
            "Booking.com",
            "Boylesports",
            "Bridaluxe",
            "BuildASign",
            "Buy.at",
            "Byte",
            "CJ Affiliate",
            "COPEAC",
            "CX Digital",
            "CafePress",
            "Cake Golden Hippo",
            "Cake Marketing",
            "Cake Spark.net",
            "CallingCards",
            "Cambabes",
            "Card Synergy",
            "Carturesti: Profitshare",
            "Cartv£o de TODOS",
            "Cash Advance",
            "Casino777",
            "CasinoCruise",
            "Catho",
            "Ceneo",
            "Changelly",
            "ChargeBee",
            "Chevron Media",
            "ChineseAN",
            "Cirtex",
            "CityAds",
            "Civitatis",
            "Claro",
            "ClassPass",
            "Cleverbridge",
            "ClickAgain",
            "ClickBank",
            "ClickBooth",
            "ClickFunnels",
            "ClickTripz",
            "Clickdealer",
            "Clickwise (Pampa Network)",
            "ClubMed",
            "Code Canyon",
            "Codere",
            "CoinMama",
            "Coinbase",
            "Collegedunia",
            "Collette Vacations",
            "ComeOn",
            "Commission Factory",
            "Commission Monster",
            "Commission River",
            "Commission Soup",
            "CommunicationAds",
            "ConversionX",
            "Convert2Media",
            "ConvertKit",
            "Coral",
            "Cougars Avenue",
            "Coupon Codes: Fun.com",
            "Credit",
            "Credit Bank - Open Sky",
            "Credit Karma",
            "Credit One",
            "Credit Sesame",
            "CreditStrong",
            "Creditstar",
            "Cuelinks",
            "DGMax",
            "Dafiti",
            "Daisycon",
            "Dark Blue",
            "Date.com",
            "Dedicated Media",
            "Dental Plans",
            "Design Essentials",
            "Despegar",
            "Digital Advisor",
            "DigitalMojo",
            "Direct Agents",
            "DoAffiliate",
            "Dot5hosting",
            "Draft Kings",
            "Dreamhost",
            "Drueck Glueck",
            "Duomai",
            "Durchblicker",
            "E*TRADE",
            "EVO Banco",
            "EWA Network",
            "Easyflirt",
            "Eaze",
            "Ebates",
            "Ebates: Referrals",
            "Ebay Partner Network",
            "Ecomobi",
            "Edenflirt",
            "Effiliation",
            "Eficads",
            "Electronic Arts",
            "Elegant Themes",
            "Emirates: AdmitAd",
            "Emirates: CJ",
            "Emirates: PHG",
            "Emirates: Tradetracker",
            "EnGrande",
            "Envato",
            "Envato Marketplace",
            "Etihad",
            "EuroAds",
            "Everbank",
            "Everflow: Green Roads",
            "Evergreen FX",
            "Exactag",
            "Exness",
            "ExpressVPN",
            "FXCM Tracking",
            "Fanduel",
            "FashionDays",
            "Fastdomain",
            "Fatcow",
            "Financeads",
            "FindLaw",
            "Fiverr",
            "FlexOffers",
            "FlexOffers - flexlinks",
            "Flipkart",
            "Flirt",
            "Flowkey",
            "Fly Media",
            "Flywheel",
            "Foreo",
            "ForexTB",
            "FortuneJack",
            "Fosina Offers",
            "Fotolia",
            "FriendFinder Networks",
            "G2A",
            "Gain Capital",
            "Gala Partners",
            "Gearbest",
            "Gelirortaklari",
            "Genesis Financial",
            "GenesisCasino",
            "Get Local Dentist",
            "GetResponse",
            "Glispa",
            "GlobalHealingCenter",
            "GlobalWide Media",
            "GlobalWide Media (Neverblue)",
            "Glopss",
            "GoDaddy",
            "GoNitro",
            "GoodGame",
            "Google Affiliate Network",
            "Grammarly",
            "Graphic River",
            "Green Dot",
            "Green Money Lines",
            "Green Smoke",
            "GreenGeeks",
            "Grinus",
            "GroupM",
            "Groupon",
            "GrubHub",
            "Gusto Referral",
            "Haloboard",
            "Has Offers",
            "Headway",
            "Hercle",
            "HideMyAss",
            "HitmanPro",
            "Hitpath",
            "HomeAway",
            "Honest Paws",
            "HootSuite",
            "HostGator IN",
            "HostNine",
            "Hostgator",
            "Hostmonster",
            "HotelsCombined",
            "Httpool",
            "Hydra",
            "I Am Naughty",
            "I Want U Web",
            "ID Finance",
            "IG",
            "IHG",
            "IOBit",
            "IPF Digital",
            "IX Web Hosting",
            "Ibibo",
            "Identity Guard",
            "Ignite Bingo",
            "Impact",
            "Income Access",
            "Incorporate.com",
            "Indexa Network",
            "Indoleads",
            "Infomercial.TV",
            "Inmotion",
            "Insparx",
            "Integrate",
            "International Vapor",
            "Internet Power",
            "Inuvo - Kowabunga",
            "Investintech",
            "Involve.Asia",
            "Iolo",
            "Ipage",
            "Ipower",
            "Jampp",
            "Jet.com",
            "Jumia",
            "Justhost",
            "Kabam",
            "Kaboo",
            "Karamba",
            "Kayak",
            "KeepCollective",
            "Kelkoo",
            "Keniks",
            "Kiiroo",
            "King Billy Casino",
            "Kinguin",
            "Kiwi.com Tequila",
            "Klook",
            "Klook: Affiliate",
            "Klook: Dynamic",
            "Klook: General",
            "Komli",
            "Lazada",
            "LeadFlash",
            "Leadgid",
            "LeadsMarket",
            "Lendio",
            "Lenovo Internal",
            "LeoVegas",
            "Lexington Law",
            "Lifelock",
            "Lightinthebox",
            "Lime Credit",
            "Linio",
            "LinkConnector",
            "LinkOffers",
            "LinkTrust",
            "Linkbux",
            "Live Partners",
            "LivePerson",
            "LivingDirect",
            "Logispin",
            "Lomadee",
            "Loopmasters",
            "LoveAholics",
            "Luckia",
            "Lyft",
            "Lyft: Driver",
            "M4N",
            "MGM Resorts International",
            "MLT Vacations",
            "MUNDO Media",
            "MYYL",
            "Maca Team",
            "Magix",
            "MailerLite",
            "Marathon Bet",
            "Market Leverage",
            "Markets.com",
            "Marley Spoon",
            "Masoffer",
            "Matchbook",
            "Maternia",
            "Matomy",
            "MaxBounty",
            "MediaTrust",
            "Meetic",
            "MercadoLibre",
            "Merkur",
            "Miche",
            "Mindspark",
            "Mindspark - Partner",
            "Mindspark - SubID",
            "Miniinthebox",
            "Miracle Noodle",
            "Mobistealth",
            "Modanisa",
            "MonetizeIt",
            "Moneybookers",
            "Mopubi",
            "MoreNiche",
            "MrGreen",
            "MrSkinCash",
            "Musicians Recommend",
            "MyHammer",
            "Namecheap",
            "Nationwide Card Services",
            "Naughty Date",
            "NetBooster",
            "NetMargin",
            "NeverBlueAds (Old)",
            "NexTag",
            "Next Insurance",
            "NextReflex",
            "Nike",
            "NordVPN",
            "NordicBet",
            "OMD Affiliates",
            "Odyssey",
            "OfferForge",
            "Offershot",
            "One Technologies",
            "Onnit",
            "Opicle",
            "Opie Network",
            "OptiMind",
            "Optimal Fusion",
            "Optimise Media",
            "PaddyPower",
            "PandaSecurity",
            "Parfumdreams",
            "Partner-Ads",
            "PartnerFusion",
            "PartnerStack",
            "Partnerize",
            "Payoneer",
            "Payoom",
            "Peerfly",
            "Pepperjam",
            "Pepperstone Affiliate",
            "Personal Loans",
            "Photo Dune",
            "Pimsleur",
            "Pipedrive",
            "Placeit",
            "Plarium",
            "PlayMillion",
            "Playojo",
            "PluginBoutique",
            "Podia",
            "PopShops",
            "Popular Marketing",
            "PostHaus",
            "Preply",
            "Pressed Juicery",
            "PriceRunner",
            "Pricegrabber - OLD",
            "Printful",
            "Proactiv",
            "Profitshare",
            "Pronto",
            "Public-Idees",
            "Publicis",
            "PureVPN",
            "QatarAirways",
            "QualityClick Spark.net",
            "QuickFlirt",
            "QuinnStreet",
            "Quip",
            "Qwik Media",
            "Rakuten Affiliate Network",
            "Rank Group",
            "Referrals: Amazon",
            "Referrals: American Giant",
            "Referrals: Birchbox",
            "Referrals: Cap 1 360",
            "Referrals: Casper",
            "Referrals: Casper (Old)",
            "Referrals: Choice Hotels",
            "Referrals: Dropbox",
            "Referrals: Evernote",
            "Referrals: Eversave",
            "Referrals: Extole",
            "Referrals: Extole (Old)",
            "Referrals: Extole - Peapod - OLD",
            "Referrals: Extole - SendGrid",
            "Referrals: ExtraBux",
            "Referrals: Fancyhands",
            "Referrals: Fanduel",
            "Referrals: FreeAgent",
            "Referrals: Fresh Direct",
            "Referrals: Gilt",
            "Referrals: Google Apps",
            "Referrals: Great Bridge",
            "Referrals: Groupon",
            "Referrals: Hotel Tonight",
            "Referrals: Hulu Plus",
            "Referrals: Ibotta",
            "Referrals: JetSetter",
            "Referrals: Kiva",
            "Referrals: LevelUp",
            "Referrals: LikeTwice",
            "Referrals: LiveScribe",
            "Referrals: Marriott",
            "Referrals: Media Temple",
            "Referrals: NetSpend",
            "Referrals: One Kings Lane",
            "Referrals: One.com",
            "Referrals: Plink",
            "Referrals: Rue La La",
            "Referrals: SEOmoz",
            "Referrals: Score Big",
            "Referrals: ShopBop",
            "Referrals: Sierra TP",
            "Referrals: Smugmug",
            "Referrals: SoFi",
            "Referrals: Swagbucks",
            "Referrals: TaskRabbit",
            "Referrals: ThreadUp",
            "Referrals: TripAlertz",
            "Referrals: VP USA",
            "Referrals: VenMo",
            "Referrals: Verizon FIOS",
            "Referrals: Vitacost",
            "Referrals: Zipcar",
            "Referrals: Zulily",
            "Referrals: minted",
            "Refersion: BedJet",
            "Refersion: BuiltBar",
            "Refersion: Daniella Shevel",
            "Refersion: Green Roads",
            "Refersion: Keyto",
            "Refersion: Needink",
            "Refersion: PhoneSoap",
            "Refersion: SLEEFS",
            "Refersion: Vanna Belt",
            "Refersion: WetPlants",
            "RegNow",
            "Reklamaction",
            "Rentalcars.com",
            "Rentcars.com",
            "RevOffers",
            "RevenueWire",
            "ReviMedia",
            "RingRevenue",
            "RiptApparel",
            "Rise",
            "Rizk",
            "Roman",
            "Royal Panda",
            "Rozetka",
            "SEO",
            "SQI",
            "SalesMedia",
            "Salestube",
            "Seamless",
            "SelfLender",
            "SemRush",
            "SeoWebHosting",
            "SexyAvenue",
            "Shaadi",
            "ShareASale",
            "Sheet Music Plus",
            "Shein",
            "ShoeDazzle",
            "Shopify",
            "Shopping.com - Dealtime",
            "Shopstylers",
            "Shoptime",
            "Shopzilla - Bizrate",
            "Sigma Beauty",
            "Signitech",
            "SilverTap",
            "SimpleTuition",
            "SimplyInk",
            "SiteGround",
            "Sixt",
            "Skimlinks",
            "Skyscanner CPC",
            "Skyscanner Partner",
            "Sloty",
            "Smarkets",
            "SmartDNS",
            "SmartDNS (Old)",
            "SmartSheet",
            "Smartresponse",
            "Snow",
            "Soap Media",
            "Soicos",
            "Solavei",
            "Sophos",
            "SpinIt",
            "Spokeo",
            "Sportsbet",
            "Star Stable - AD2",
            "Star Stable - ADMITAD",
            "Star Stable - ADVENDOR",
            "Star Stable - ADVGAME",
            "Star Stable - CASULO",
            "Star Stable - CITYADS",
            "Star Stable - CPMSTAR",
            "Star Stable - GAMEBASSA",
            "Star Stable - GAMESVID",
            "Star Stable - IQU2",
            "Star Stable - PWN",
            "StarsGroup",
            "Stella and Dot",
            "Stitch Fix",
            "StrongVPN",
            "StrongView",
            "Submarino",
            "SuperCloset",
            "Supermetrics",
            "SurLaTable",
            "SureHits",
            "Surfshark",
            "SwimOutlet",
            "TBP",
            "TRS",
            "TargetCircle",
            "Team National",
            "Telestream",
            "TemplateMonster",
            "The Affiliate Gateway",
            "The Useful",
            "TheZebra.com",
            "ThemeForest",
            "Themify",
            "Thrive Market",
            "TigerSheds",
            "TimeOne",
            "Tipico",
            "TomTop",
            "Trada Casino",
            "TradeDoubler",
            "TradeTracker",
            "TradingView",
            "TransferWise",
            "TravelPayouts",
            "Trivago: CIP",
            "Trusted ID - Hitpath",
            "Turbozaim",
            "Twilio",
            "Twine Digital",
            "Tyroo",
            "Uber: Driver",
            "Uber: Rider",
            "Udemy",
            "Unity",
            "Usana",
            "VIP Affiliate Network",
            "VRBO",
            "ValueCommerce",
            "Viajes El Corte Ingles",
            "Video Hive",
            "VigLink",
            "Vistaprint",
            "Voisinssolitaires",
            "Vorcu",
            "W4",
            "Wargaming",
            "Weach",
            "WebGains",
            "Webhostinghub",
            "Wells Fargo",
            "Welp",
            "William Hill",
            "Wix",
            "Wixstars",
            "Wrike",
            "XM",
            "Xapads",
            "Xcams",
            "Xcoins.com",
            "Xflirt",
            "Xtend-Life",
            "YMax",
            "YesStyle",
            "Young Living Enroller",
            "Young Living Sponsor",
            "Zanox",
            "Zazzle",
            "Zenith BuyGoods",
            "Zenith HasOffers",
            "Zenith MaxWeb",
            "Zeta Interactive",
            "Zoosk",
            "Zopim",
            "affiliaXe",
            "affilinet",
            "akmg",
            "alpharooms.com",
            "bbsClicks",
            "bol.com",
            "brandconversions",
            "clixGalore",
            "dgm",
            "dtm partners",
            "eBay Partner Network",
            "eHub",
            "eSpoluprace",
            "eToro",
            "ehi",
            "iCubesWire",
            "iDev",
            "iHerb",
            "iHerb: Subaffiliates",
            "mSpy",
            "netaffiliation",
            "oneNetworkDirect",
            "pCloud",
            "paid on results",
            "vCommission",
            "vCommission (old)",
            "vapor4life",
            "Knows",
            "Know Platfrom",
            "CityAds",
            "Admitads",
            "Adpump",
            "Actionpay",
            "Sellaction",
            "SkimLinks",
            "A2Hosting",
            "Acesstrade",
            "Addiliate",
            "AdmitAd",
            "Adrecords",
            "Affiliago",
            "Affilired",
            "Affonix",
            "Agoda",
            "Alibaba",
            "Americanas",
            "Arabclicks",
            "ArabyAds",
            "BeNaughty.com",
            "Bet365",
            "BigRock",
            "BlueHost IN",
            "CJ Affiliate",
            "ChineseAN",
            "Clickdealer",
            "Clickwise (Pampa Network)",
            "ConvertKit",
            "Ebay Partner Network",
            "Emirates: AdmitAd",
            "Etihad",
            "ExpressVPN",
            "FlexOffers",
            "Gearbest",
            "GroupM",
            "HostGator IN",
            "Impact",
            "Indoleads",
            "Jumia",
            "Kiwi.com Tequila",
            "Klook",
            "Komli",
            "Lazada",
            "Miniinthebox",
            "Opicle",
            "Opie Network",
            "Peerfly",
            "Pepperjam",
            "SemRush",
            "Shaadi",
            "ShareASale",
            "Shopify",
            "Skyscanner Partner",
            "Udemy",
            "VIP Affiliate Network",
            "paid on results",
            "vCommission",
        ];
        //Inserting all above network list one-by-one
        foreach($network_list as $n){
            AffiliateNetwork::create([
                'name' => $n,
            ]);
        }
    }
}