-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 15 oct. 2021 à 16:34
-- Version du serveur : 10.4.20-MariaDB
-- Version de PHP : 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `overtest`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(10) UNSIGNED NOT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(80) NOT NULL,
  `chapo` varchar(120) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` date NOT NULL,
  `last_update` date DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `author_id`, `category_id`, `title`, `chapo`, `content`, `created_at`, `last_update`, `img_path`) VALUES
(1, 1, 2, 'mon premier article', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Tibi hoc incredibile, quod beatissimum. Sedulo, inquam, faciam. De vacuitate doloris eadem sententia erit. Ut pulsi recurrant? Conferam avum tuum Drusum cum C. Aut haec tibi, Torquate, sunt vituperanda aut patrocinium voluptatis repudiandum. \r\n\r\nDuo Reges: constructio interrete. Verum hoc idem saepe faciamus. Quid enim possumus hoc agere divinius? \r\n\r\nSed nimis multa. Quis istum dolorem timet? De quibus cupio scire quid sentias. Si quae forte-possumus. Tanta vis admonitionis inest in locis; Iam id ipsum absurdum, maximum malum neglegi. \r\n\r\nQuo modo? Praeclarae mortes sunt imperatoriae; Sed haec nihil sane ad rem; Nihil opus est exemplis hoc facere longius. Eam tum adesse, cum dolor omnis absit; \r\n\r\nNonne igitur tibi videntur, inquit, mala? Quis est tam dissimile homini. Et quidem, inquit, vehementer errat; Urgent tamen et nihil remittunt. \r\n', '2021-09-28', NULL, 'article-0001.jpg'),
(2, 2, 1, 'un autre aricle', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nos vero, inquit ille; Ita enim vivunt quidam, ut eorum vita refellatur oratio. An me, inquam, nisi te audire vellem, censes haec dicturum fuisse? Idemque diviserunt naturam hominis in animum et corpus. Duo Reges: constructio interrete. Perturbationes autem nulla naturae vi commoventur, omniaque ea sunt opiniones ac iudicia levitatis. Non quaeritur autem quid naturae tuae consentaneum sit, sed quid disciplinae. Scisse enim te quis coarguere possit? Duarum enim vitarum nobis erunt instituta capienda. Deinde prima illa, quae in congressu solemus: Quid tu, inquit, huc? \r\n\r\nEorum enim est haec querela, qui sibi cari sunt seseque diligunt. Hanc ergo intuens debet institutum illud quasi signum absolvere. Sed vos squalidius, illorum vides quam niteat oratio. Omnes enim iucundum motum, quo sensus hilaretur. At iste non dolendi status non vocatur voluptas. Quo modo autem optimum, si bonum praeterea nullum est? Atqui perspicuum est hominem e corpore animoque constare, cum primae sint animi partes, secundae corporis. Ex quo intellegitur officium medium quiddam esse, quod neque in bonis ponatur neque in contrariis. ', '2021-09-28', NULL, 'article-0002.jpg'),
(3, 1, 1, 'Déjà le troisième ?', 'le chapo de l\'article', 'Nec mihi illud dixeris: Haec enim ipsa mihi sunt voluptati, et erant illa Torquatis. Illis videtur, qui illud non dubitant bonum dicere -; Quid, de quo nulla dissensio est? Varietates autem iniurasque fortunae facile veteres philosophorum praeceptis instituta vita superabat. Quod autem principium officii quaerunt, melius quam Pyrrho; Duo Reges: constructio interrete. \r\n\r\nQuis non odit sordidos, vanos, leves, futtiles? Sed residamus, inquit, si placet. Haec dicuntur fortasse ieiunius; Tu autem negas fortem esse quemquam posse, qui dolorem malum putet. Tum Quintus: Est plane, Piso, ut dicis, inquit. Non igitur bene. \r\n\r\nQuis enim redargueret? Naturales divitias dixit parabiles esse, quod parvo esset natura contenta. Nam nec vir bonus ac iustus haberi debet qui, ne malum habeat, abstinet se ab iniuria. Propter nos enim illam, non propter eam nosmet ipsos diligimus. Graece donan, Latine voluptatem vocant. Nam ante Aristippus, et ille melius. Quicquid porro animo cernimus, id omne oritur a sensibus; Ex ea difficultate illae fallaciloquae, ut ait Accius, malitiae natae sunt. ', '2021-09-28', NULL, 'article-0003.jpg'),
(4, 1, 2, 'Enfin le dernier', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Itaque fecimus. Atque haec ita iustitiae propria sunt, ut sint virtutum reliquarum communia. Primum cur ista res digna odio est, nisi quod est turpis? Serpere anguiculos, nare anaticulas, evolare merulas, cornibus uti videmus boves, nepas aculeis. Quae similitudo in genere etiam humano apparet. \r\n\r\nHoc ne statuam quidem dicturam pater aiebat, si loqui posset. Equidem soleo etiam quod uno Graeci, si aliter non possum, idem pluribus verbis exponere. Etsi qui potest intellegi aut cogitari esse aliquod animal, quod se oderit? Haec dicuntur fortasse ieiunius; Partim cursu et peragratione laetantur, congregatione aliae coetum quodam modo civitatis imitantur; Quamvis enim depravatae non sint, pravae tamen esse possunt. \r\n\r\nIlla videamus, quae a te de amicitia dicta sunt. Quorum sine causa fieri nihil putandum est. Sin tantum modo ad indicia veteris memoriae cognoscenda, curiosorum. Minime vero probatur huic disciplinae, de qua loquor, aut iustitiam aut amicitiam propter utilitates adscisci aut probari. Quod autem magnum dolorem brevem, longinquum levem esse dicitis, id non intellego quale sit. Eam tum adesse, cum dolor omnis absit; Quid igitur, inquit, eos responsuros putas? \r\n\r\nBona autem corporis huic sunt, quod posterius posui, similiora. Primum cur ista res digna odio est, nisi quod est turpis? Sed nonne merninisti licere mihi ista probare, quae sunt a te dicta? Sic vester sapiens magno aliquo emolumento commotus cicuta, si opus erit, dimicabit. \r\n\r\nSi enim ad populum me vocas, eum. Duo Reges: constructio interrete. Ergo hoc quidem apparet, nos ad agendum esse natos. Non quam nostram quidem, inquit Pomponius iocans; Sed potestne rerum maior esse dissensio? Non est igitur voluptas bonum. Quae quo sunt excelsiores, eo dant clariora indicia naturae. Iam id ipsum absurdum, maximum malum neglegi. Quis enim est, qui non videat haec esse in natura rerum tria? Sed ad bona praeterita redeamus. \r\n\r\n', '2021-09-28', NULL, 'article-0004.jpg'),
(5, 1, 1, 'le dernier c\'est quand ?', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Deinde disputat, quod cuiusque generis animantium statui deceat extremum. Atqui eorum nihil est eius generis, ut sit in fine atque extrerno bonorum. \r\n\r\nNec lapathi suavitatem acupenseri Galloni Laelius anteponebat, sed suavitatem ipsam neglegebat; Itaque mihi non satis videmini considerare quod iter sit naturae quaeque progressio. Tubulo putas dicere? Non igitur potestis voluptate omnia dirigentes aut tueri aut retinere virtutem. \r\n\r\nQuid enim de amicitia statueris utilitatis causa expetenda vides. Quid ait Aristoteles reliquique Platonis alumni? Non igitur de improbo, sed de callido improbo quaerimus, qualis Q. Quid ergo attinet dicere: Nihil haberem, quod reprehenderem, si finitas cupiditates haberent? \r\n\r\nSummum ením bonum exposuit vacuitatem doloris; In eo enim positum est id, quod dicimus esse expetendum. Duo Reges: constructio interrete. Nec lapathi suavitatem acupenseri Galloni Laelius anteponebat, sed suavitatem ipsam neglegebat; \r\n\r\nHanc ergo intuens debet institutum illud quasi signum absolvere. Age nunc isti doceant, vel tu potius quis enim ista melius? Qui-vere falsone, quaerere mittimus-dicitur oculis se privasse; Cenasti in vita numquam bene, cum omnia in ista Consumis squilla atque acupensere cum decimano. Qui enim existimabit posse se miserum esse beatus non erit. Deque his rebus satis multa in nostris de re publica libris sunt dicta a Laelio. Tum Piso: Quoniam igitur aliquid omnes, quid Lucius noster? Multoque hoc melius nos veriusque quam Stoici. An dubium est, quin virtus ita maximam partem optineat in rebus humanis, ut reliquas obruat? Tu quidem reddes; Iam id ipsum absurdum, maximum malum neglegi. \r\n\r\n', '2021-09-28', NULL, 'article-0005.jpg'),
(6, 1, 1, 'un super article qui tue', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nec mihi illud dixeris: Haec enim ipsa mihi sunt voluptati, et erant illa Torquatis. Illis videtur, qui illud non dubitant bonum dicere -; Quid, de quo nulla dissensio est? Varietates autem iniurasque fortunae facile veteres philosophorum praeceptis instituta vita superabat. Quod autem principium officii quaerunt, melius quam Pyrrho; Duo Reges: constructio interrete. \r\n\r\nQuis non odit sordidos, vanos, leves, futtiles? Sed residamus, inquit, si placet. Haec dicuntur fortasse ieiunius; Tu autem negas fortem esse quemquam posse, qui dolorem malum putet. Tum Quintus: Est plane, Piso, ut dicis, inquit. Non igitur bene. \r\n\r\nQuis enim redargueret? Naturales divitias dixit parabiles esse, quod parvo esset natura contenta. Nam nec vir bonus ac iustus haberi debet qui, ne malum habeat, abstinet se ab iniuria. Propter nos enim illam, non propter eam nosmet ipsos diligimus. Graece donan, Latine voluptatem vocant. Nam ante Aristippus, et ille melius. Quicquid porro animo cernimus, id omne oritur a sensibus; Ex ea difficultate illae fallaciloquae, ut ait Accius, malitiae natae sunt. ', '2021-09-28', NULL, 'article-0006.jpg'),
(7, 2, 1, 'un article pas trop bien...', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed haec quidem liberius ab eo dicuntur et saepius. Familiares nostros, credo, Sironem dicis et Philodemum, cum optimos viros, tum homines doctissimos. At iam decimum annum in spelunca iacet. At ego quem huic anteponam non audeo dicere; Scio enim esse quosdam, qui quavis lingua philosophari possint; Duo Reges: constructio interrete. Mihi, inquam, qui te id ipsum rogavi? \r\n\r\nRespondent extrema primis, media utrisque, omnia omnibus. Nunc haec primum fortasse audientis servire debemus. Duarum enim vitarum nobis erunt instituta capienda. Paria sunt igitur. Et harum quidem rerum facilis est et expedita distinctio. An eum discere ea mavis, quae cum plane perdidiceriti nihil sciat? Sed quanta sit alias, nunc tantum possitne esse tanta. Et non ex maxima parte de tota iudicabis? \r\n\r\nNon laboro, inquit, de nomine. Indicant pueri, in quibus ut in speculis natura cernitur. Omnia contraria, quos etiam insanos esse vultis. Et certamen honestum et disputatio splendida! omnis est enim de virtutis dignitate contentio. Quo invento omnis ab eo quasi capite de summo bono et malo disputatio ducitur. Sin autem eos non probabat, quid attinuit cum iis, quibuscum re concinebat, verbis discrepare? Mihi vero, inquit, placet agi subtilius et, ut ipse dixisti, pressius. In qua quid est boni praeter summam voluptatem, et eam sempiternam? Quid, si etiam iucunda memoria est praeteritorum malorum? Idem etiam dolorem saepe perpetiuntur, ne, si id non faciant, incidant in maiorem. Vitiosum est enim in dividendo partem in genere numerare. \r\n\r\n', '2021-09-28', NULL, 'article-0007.jpg'),
(8, 2, 2, 'un article qui parle d\'un truc, c\'est cool !!!!!', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Idem fecisset Epicurus, si sententiam hanc, quae nunc Hieronymi est, coniunxisset cum Aristippi vetere sententia. Si sapiens, ne tum quidem miser, cum ab Oroete, praetore Darei, in crucem actus est. Haec bene dicuntur, nec ego repugno, sed inter sese ipsa pugnant. Quo plebiscito decreta a senatu est consuli quaestio Cn. Age sane, inquam. Quodsi ipsam honestatem undique pertectam atque absolutam. Duo Reges: constructio interrete. Quod cum dixissent, ille contra. \r\n\r\nEgone non intellego, quid sit don Graece, Latine voluptas? Si quicquam extra virtutem habeatur in bonis. Quae autem natura suae primae institutionis oblita est? Bestiarum vero nullum iudicium puto. Portenta haec esse dicit, neque ea ratione ullo modo posse vivi; Ex ea difficultate illae fallaciloquae, ut ait Accius, malitiae natae sunt. \r\n\r\nQuae duo sunt, unum facit. Tu enim ista lenius, hic Stoicorum more nos vexat. Atqui haec patefactio quasi rerum opertarum, cum quid quidque sit aperitur, definitio est. Quae tamen a te agetur non melior, quam illae sunt, quas interdum optines. Haec non erant eius, qui innumerabilis mundos infinitasque regiones, quarum nulla esset ora, nulla extremitas, mente peragravisset. Perturbationes autem nulla naturae vi commoventur, omniaque ea sunt opiniones ac iudicia levitatis. Beatus autem esse in maximarum rerum timore nemo potest. Nummus in Croesi divitiis obscuratur, pars est tamen divitiarum. \r\n\r\n', '2021-09-28', NULL, 'article-0008.jpg'),
(9, 1, 1, 'plus d\'inspiration pour le titre, déjà que c\'était moyen...', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quarum ambarum rerum cum medicinam pollicetur, luxuriae licentiam pollicetur. Sed ad haec, nisi molestum est, habeo quae velim. \r\n\r\nNon semper, inquam; Hoc ipsum elegantius poni meliusque potuit. An eiusdem modi? Quae qui non vident, nihil umquam magnum ac cognitione dignum amaverunt. Quare si potest esse beatus is, qui est in asperis reiciendisque rebus, potest is quoque esse. Quod cum dixissent, ille contra. At, illa, ut vobis placet, partem quandam tuetur, reliquam deserit. Quos quidem tibi studiose et diligenter tractandos magnopere censeo. \r\n\r\nQuae adhuc, Cato, a te dicta sunt, eadem, inquam, dicere posses, si sequerere Pyrrhonem aut Aristonem. Sin dicit obscurari quaedam nec apparere, quia valde parva sint, nos quoque concedimus; At, si voluptas esset bonum, desideraret. Quam ob rem tandem, inquit, non satisfacit? Duo Reges: constructio interrete. Cyrenaici quidem non recusant; Verum hoc idem saepe faciamus. Inde sermone vario sex illa a Dipylo stadia confecimus. An me, inquam, nisi te audire vellem, censes haec dicturum fuisse? \r\n\r\n', '2021-09-28', NULL, 'article-0009.jpg'),
(10, 1, 2, 'de quoi ça parle ? lis, tu verras !', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quarum ambarum rerum cum medicinam pollicetur, luxuriae licentiam pollicetur. Sed ad haec, nisi molestum est, habeo quae velim. \r\n\r\nNon semper, inquam; Hoc ipsum elegantius poni meliusque potuit. An eiusdem modi? Quae qui non vident, nihil umquam magnum ac cognitione dignum amaverunt. Quare si potest esse beatus is, qui est in asperis reiciendisque rebus, potest is quoque esse. Quod cum dixissent, ille contra. At, illa, ut vobis placet, partem quandam tuetur, reliquam deserit. Quos quidem tibi studiose et diligenter tractandos magnopere censeo. \r\n\r\nQuae adhuc, Cato, a te dicta sunt, eadem, inquam, dicere posses, si sequerere Pyrrhonem aut Aristonem. Sin dicit obscurari quaedam nec apparere, quia valde parva sint, nos quoque concedimus; At, si voluptas esset bonum, desideraret. Quam ob rem tandem, inquit, non satisfacit? Duo Reges: constructio interrete. Cyrenaici quidem non recusant; Verum hoc idem saepe faciamus. Inde sermone vario sex illa a Dipylo stadia confecimus. An me, inquam, nisi te audire vellem, censes haec dicturum fuisse? ', '2021-09-28', NULL, 'article-0010.jpg'),
(11, 2, 1, 'lorem lorem lorem lorem, lo quoi ?', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ille vero, si insipiens-quo certe, quoniam tyrannus -, numquam beatus; Ut in voluptate sit, qui epuletur, in dolore, qui torqueatur. Quid vero? Itaque vides, quo modo loquantur, nova verba fingunt, deserunt usitata. Odium autem et invidiam facile vitabis. Vidit Homerus probari fabulam non posse, si cantiunculis tantus irretitus vir teneretur; Duo Reges: constructio interrete. Tum ille: Tu autem cum ipse tantum librorum habeas, quos hic tandem requiris? Igitur neque stultorum quisquam beatus neque sapientium non beatus. Qui igitur convenit ab alia voluptate dicere naturam proficisci, in alia summum bonum ponere? An hoc usque quaque, aliter in vita? Sed mehercule pergrata mihi oratio tua. \r\n\r\nNum igitur eum postea censes anxio animo aut sollicito fuisse? Quodsi ipsam honestatem undique pertectam atque absolutam. Aliam vero vim voluptatis esse, aliam nihil dolendi, nisi valde pertinax fueris, concedas necesse est. -, sed ut hoc iudicaremus, non esse in iis partem maximam positam beate aut secus vivendi. \r\n', '2021-09-28', NULL, 'article-0011.jpg'),
(12, 1, 1, 'un titre de ouf', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Deinde disputat, quod cuiusque generis animantium statui deceat extremum. Atqui eorum nihil est eius generis, ut sit in fine atque extrerno bonorum. \r\n\r\nNec lapathi suavitatem acupenseri Galloni Laelius anteponebat, sed suavitatem ipsam neglegebat; Itaque mihi non satis videmini considerare quod iter sit naturae quaeque progressio. Tubulo putas dicere? Non igitur potestis voluptate omnia dirigentes aut tueri aut retinere virtutem. \r\n\r\nQuid enim de amicitia statueris utilitatis causa expetenda vides. Quid ait Aristoteles reliquique Platonis alumni? Non igitur de improbo, sed de callido improbo quaerimus, qualis Q. Quid ergo attinet dicere: Nihil haberem, quod reprehenderem, si finitas cupiditates haberent? \r\n\r\nSummum ením bonum exposuit vacuitatem doloris; In eo enim positum est id, quod dicimus esse expetendum. Duo Reges: constructio interrete. Nec lapathi suavitatem acupenseri Galloni Laelius anteponebat, sed suavitatem ipsam neglegebat; \r\n\r\nHanc ergo intuens debet institutum illud quasi signum absolvere. Age nunc isti doceant, vel tu potius quis enim ista melius? Qui-vere falsone, quaerere mittimus-dicitur oculis se privasse; Cenasti in vita numquam bene, cum omnia in ista Consumis squilla atque acupensere cum decimano. Qui enim existimabit posse se miserum esse beatus non erit. Deque his rebus satis multa in nostris de re publica libris sunt dicta a Laelio. Tum Piso: Quoniam igitur aliquid omnes, quid Lucius noster? Multoque hoc melius nos veriusque quam Stoici. An dubium est, quin virtus ita maximam partem optineat in rebus humanis, ut reliquas obruat? Tu quidem reddes; Iam id ipsum absurdum, maximum malum neglegi. \r\n\r\n', '2021-09-28', NULL, 'article-0012.jpg'),
(13, 1, 1, 'de quoi ça parle ? lis, tu verras !', 'le chapo de l\'article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quarum ambarum rerum cum medicinam pollicetur, luxuriae licentiam pollicetur. Sed ad haec, nisi molestum est, habeo quae velim. \r\n\r\nNon semper, inquam; Hoc ipsum elegantius poni meliusque potuit. An eiusdem modi? Quae qui non vident, nihil umquam magnum ac cognitione dignum amaverunt. Quare si potest esse beatus is, qui est in asperis reiciendisque rebus, potest is quoque esse. Quod cum dixissent, ille contra. At, illa, ut vobis placet, partem quandam tuetur, reliquam deserit. Quos quidem tibi studiose et diligenter tractandos magnopere censeo. \r\n\r\nQuae adhuc, Cato, a te dicta sunt, eadem, inquam, dicere posses, si sequerere Pyrrhonem aut Aristonem. Sin dicit obscurari quaedam nec apparere, quia valde parva sint, nos quoque concedimus; At, si voluptas esset bonum, desideraret. Quam ob rem tandem, inquit, non satisfacit? Duo Reges: constructio interrete. Cyrenaici quidem non recusant; Verum hoc idem saepe faciamus. Inde sermone vario sex illa a Dipylo stadia confecimus. An me, inquam, nisi te audire vellem, censes haec dicturum fuisse? ', '2021-09-28', NULL, 'article-0013.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `avatar`
--

CREATE TABLE `avatar` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(45) NOT NULL,
  `img_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='man client / woman client / admin';

--
-- Déchargement des données de la table `avatar`
--

INSERT INTO `avatar` (`id`, `type`, `img_path`) VALUES
(1, 'admin', 'avatar-admin.svg'),
(2, 'woman', 'avatar-woman.svg'),
(3, 'man', 'avatar-man.svg');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `category` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `category`) VALUES
(1, 'Linux'),
(2, 'PHP'),
(3, 'Raspberry'),
(4, 'POO');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `role` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='member - admin';

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'admin'),
(2, 'membre');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `pseudo` varchar(80) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `pseudo`, `email`, `password`, `avatar_id`) VALUES
(1, 'Albert', 'Dumachin', 'dudule', 'albert.duchmol@gmail.com', 'amlb255+', 1),
(2, 'Martine', 'Alaplage', 'Marta', 'martlamart@yahoo.fr', 'monpass', 2);

-- --------------------------------------------------------

--
-- Structure de la table `user_has_role`
--

CREATE TABLE `user_has_role` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user_has_role`
--

INSERT INTO `user_has_role` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_article_user1_idx` (`author_id`),
  ADD KEY `fk_article_category1_idx` (`category_id`);

--
-- Index pour la table `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_avatar1_idx` (`avatar_id`);

--
-- Index pour la table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `fk_user_has_role_role1_idx` (`role_id`),
  ADD KEY `fk_user_has_role_user_idx` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `avatar`
--
ALTER TABLE `avatar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `fk_article_category1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_article_user1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_avatar1` FOREIGN KEY (`avatar_id`) REFERENCES `avatar` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `user_has_role`
--
ALTER TABLE `user_has_role`
  ADD CONSTRAINT `fk_user_has_role_role1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_has_role_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
