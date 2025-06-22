-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 11:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vijesti_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

DROP TABLE IF EXISTS `korisnik`;
CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `ime` varchar(255) NOT NULL,
  `prezime` varchar(255) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka_hash` varchar(255) NOT NULL,
  `razina_prava` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `korisnicko_ime`, `lozinka_hash`, `razina_prava`) VALUES
(3, 'Korisnik', 'Test1', 'DummyUser1', '$2y$10$55yXARCnDWCu6.nZ8iwQ0uLWaMP8GGAOYUkn/IIWRAWIcUVS2Tkla', 0),
(4, 'Admin', 'Test1', 'Admin', '$2y$10$FOtz202tBaElqxLwMj6N9OrJSx/sZ0ypYLL6rTZ./q31wMdpcmGqS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vijesti`
--

DROP TABLE IF EXISTS `vijesti`;
CREATE TABLE `vijesti` (
  `id` int(11) NOT NULL,
  `datum` varchar(32) NOT NULL,
  `naslov` varchar(64) NOT NULL,
  `sazetak` text NOT NULL,
  `tekst` text NOT NULL,
  `slika` varchar(64) NOT NULL,
  `kategorija` varchar(64) NOT NULL,
  `arhiva` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vijesti`
--

INSERT INTO `vijesti` (`id`, `datum`, `naslov`, `sazetak`, `tekst`, `slika`, `kategorija`, `arhiva`) VALUES
(10, '2025-06-22', 'Margrethe Vestager ulijeva nadu liberalima – a Trump drhti', 'Potpredsjednica Europske komisije Margrethe Vestager potresa svijet tehnoloških divova...', 'Dok se Europa bori s vlastitim političkim izazovima, povjerenica EU-a za tržišno natjecanje, Margrethe Vestager, postaje svjetionik nade za liberale diljem svijeta. Njezine beskompromisne akcije protiv tehnoloških divova i monopolista izazivaju drhtavicu u redovima nekih moćnika, uključujući i Donalda Trumpa.\r\n\r\nU svijetu opterećenom populizmom i rastućim autoritarizmom, Margrethe Vestager, povjerenica Europske unije za tržišno natjecanje, izrasla je u ključnu figuru koja utjelovljuje liberalne vrijednosti i snažnu regulaciju tržišta. Njezin je rad prepoznat kao borba za pravednost i transparentnost, što joj je priskrbilo podršku onih koji se protive nekontroliranoj moći korporacija i političara.\r\n\r\nVestager je poznata po svojoj strogoj, ali dosljednoj primjeni zakona o tržišnom natjecanju. Njezine istrage i kazne protiv tehnoloških giganta poput Googlea, Applea i Amazona poslale su jasnu poruku da nitko nije iznad zakona. Ove su akcije naišle na odobravanje u krugovima koji se zalažu za razbijanje monopola i osiguravanje fer uvjeta za sve sudionike na tržištu.\r\n\r\nNjezin pristup, koji naglašava važnost otvorenog i konkurentnog tržišta, često je u suprotnosti s politikama koje zagovaraju protekcionizam i favoriziranje nacionalnih interesa. Upravo zbog toga, njezine odluke i javni istupi izazivaju nelagodu kod političara poput Donalda Trumpa, koji se zalažu za drugačiju ekonomsku i političku filozofiju. Trumpova administracija često je bila kritična prema europskoj regulaciji i njezinu utjecaju na američke tvrtke, smatrajući je preprekom slobodnoj trgovini.\r\n\r\nNadalje, Vestagerin fokus na digitalnu sferu i njezina borba protiv manipulacije podacima i zloupotrebe položaja čine je trnom u oku za one koji profitiraju od nedovoljno reguliranog digitalnog tržišta. Njezine inicijative ulijevaju nadu da će se uspostaviti globalni standardi koji će štititi građane i poticati inovacije, umjesto da dopuštaju nekolicini tvrtki dominaciju.\r\n\r\nU kontekstu rastućih globalnih tenzija i neizvjesnosti, Margrethe Vestager predstavlja simbol otpora prema nazadnim politikama i nadu u svjetliju budućnost za liberalne demokracije. Njezina nepokolebljiva posvećenost principima pravde i poštenja osigurava joj mjesto među najutjecajnijim političarima današnjice.\r\n\r\n\"Margrethe Vestager je dokaz da se integritet i principijelnost i dalje cijene u visokoj politici. Njezin rad je podsjetnik da slobodno tržište mora biti i pravedno.\" - izjava analitičara.', 'uploads/politika_vijest1.jpg', 'Politika', 0),
(11, '2025-06-22', 'Moja fotografija na stern.de - Sudjelujte u našoj foto akciji!', 'Sudjelujte u našoj foto akciji i pošaljite svoju najbolju fotografiju...', 'Imate li nevjerojatnu fotografiju koju želite podijeliti sa svijetom? Stern.de vam pruža jedinstvenu priliku! Pošaljite nam svoju najbolju fotografiju i možda baš ona bude objavljena na našem portalu!\n\nVolite fotografirati? Imate li u svojoj kolekciji slika onu jednu, posebnu, koja priča priču, bilježi nezaboravan trenutak ili jednostavno oduzima dah? Sad je vaša prilika da je podijelite s tisućama čitatelja portala stern.de! Pokrećemo veliku foto akciju i pozivamo vas da sudjelujete.\n\nTražimo fotografije koje inspiriraju, dirnu ili jednostavno pokažu svijet iz vaše jedinstvene perspektive. Nije važno jeste li profesionalni fotograf ili strastveni amater; bitna je kvaliteta i originalnost vašeg rada. Od pejzaža koji ostavljaju bez daha, preko iskrenih portreta, do trenutaka iz svakodnevnog života – sve su teme dobrodošle, pod uvjetom da su snimljene s pažnjom i kreativnošću.\n\nKako sudjelovati? Jednostavno je! Odaberite svoju najbolju fotografiju za koju smatrate da zaslužuje biti viđena. Fotografija mora biti visoke rezolucije i vaša originalna autorska djelo. Uz fotografiju, obavezno priložite i kratak opis (do 50 riječi) – što prikazuje, gdje je snimljena, ili priču koja stoji iza nje. To nam pomaže da bolje razumijemo kontekst vaše umjetnosti.\n\nSvoje fotografije i opise šaljite na našu email adresu: fotoakcija@stern.de. Molimo vas da u naslovu emaila navedete \"Moja fotografija za stern.de\". Rok za slanje fotografija je 15. srpnja 2025.. Nakon isteka roka, naš urednički tim pažljivo će pregledati sve pristigle radove.\n\nOdabrane fotografije bit će objavljene u posebnoj galeriji na našem portalu stern.de, s potpisom autora. Ovo je sjajna prilika da vaš rad dosegne široku publiku i da se istaknete u svijetu vizualnih umjetnosti. Veselimo se vašim kreativnim doprinosima i jedva čekamo otkriti vaše priče kroz objektiv!\n\n\"Kroz fotografiju, svaki pojedinac postaje pripovjedač. Podijelite svoju priču s nama!\" - poruka uredništva stern.de.', 'uploads/slikanje.jpg', 'Društvo', 0),
(12, '2025-06-22', 'Ljudi u paralelnom svijetu, udaljenom samo bacanjem kamena', 'Fotografska izložba \"Bez granica\" prikazuje živote ljudi u neobičnim okolnostima...', 'Dok se jedni bore s globalnim krizama, inflacijom i egzistencijalnim strahovima, drugi žive u naizgled nepomućenom balonu blagostanja i nesvjesnosti. Je li riječ o pukom neznanju, privilegiji ili svjesnom odbijanju suočavanja s realnošću? Pogledajmo u taj paralelni svijet koji je često \"samo bacanjem kamena\" od nas.\n\nU današnjem, globalno povezanom svijetu, informacije su nam dostupnije nego ikad prije. Vijesti o ratovima, ekonomskim padovima, klimatskim promjenama i društvenim nemirima preplavljuju naše ekrane. Ipak, postoji značajan dio populacije koji, čini se, živi potpuno izolirano od tih stvarnosti, u svojevrsnom paralelnom univerzumu gdje su takvi problemi tek apstraktni pojmovi.\n\nOvaj \"paralelni svijet\" često karakterizira financijska sigurnost koja omogućuje ignoriranje rastućih troškova života i ekonomske neizvjesnosti. Ljudi koji u njemu žive često su zaštićeni od direktnih posljedica inflacije ili recesije, što im omogućuje da se usredotoče na osobne užitke, putovanja i luksuz. Njihovi su prioriteti često usmjereni na ono što se percipira kao \"dobar život\", dok se problemi izvan njihovog uskog kruga ne percipiraju kao neposredna prijetnja.\n\nNije rijetkost da takvi pojedinci nisu nužno zli ili zlonamjerni. Ponekad je riječ o nesvjesnoj privilegiji – odrastanju u okruženju koje ih nikada nije prisililo da se suoče s teškim životnim situacijama. Za njih, svijet se vrti oko individualnih uspjeha, socijalnog statusa i materijalnog posjeda, dok kolektivna odgovornost ili suosjećanje s onima manje sretnima ostaju u drugom planu.\n\nPostoji i fenomen svjesnog odvajanja od \"negativnih\" vijesti. Neki namjerno biraju ignorirati probleme, vjerujući da im to pomaže u očuvanju mentalnog zdravlja ili produktivnosti. Iako je zdravo postaviti granice s medijima, potpuno isključivanje iz kolektivne stvarnosti može dovesti do manjka empatije i nerazumijevanja širih društvenih dinamika. To stvara jaz između različitih slojeva društva, produbljujući podjele i frustracije.\n\nOvaj jaz je posebno vidljiv u urbanim sredinama, gdje luksuzni stanovi stoje tik uz siromašne četvrti, ili gdje se na društvenim mrežama prikazuje savršen život dok se u stvarnosti mnogi bore za osnovne potrebe. Ovi \"paralelni svjetovi\" koegzistiraju, fizički blizu, ali mentalno i emocionalno udaljeni.\n\nRazumijevanje ovog fenomena ključno je za promicanje veće društvene kohezije. Tek kada se prepozna postojanje ovih paralelnih stvarnosti i kada se premosti jaz u svijesti, može se raditi na zajedničkim rješenjima za izazove koji utječu na sve, bez obzira na materijalni status.\n\n\"Najopasnije je kada se privilegija pretvori u sljepoću. Svijet je previše međusobno povezan da bismo si dopustili luksuz ignoriranja tuđe stvarnosti.\" - citat sociologa.', 'uploads/inequality.jpg', 'Društvo', 0),
(13, '2025-06-22', 'Odluka o e-romobilima - evo o čemu se radi u Saveznom vijeću', 'Rasprava o pravilima za e-romobile dostiže vrhunac...', 'Budućnost e-romobila u gradovima mogla bi doživjeti značajne promjene. Savezno vijeće raspravlja o novim propisima koji bi mogli utjecati na sve, od korisnika do gradskih uprava. Evo što se točno nalazi na stolu i zašto je ova odluka toliko važna.\n\nE-romobili su se u kratkom vremenu etablirali kao popularno prijevozno sredstvo u urbanim sredinama. Nude fleksibilnost, smanjuju prometne gužve i predstavljaju ekološki prihvatljiviju alternativu automobilima. Međutim, s njihovom sve većom prisutnošću, pojavili su se i brojni izazovi – od parkiranja i sigurnosti do uređenja javnog prostora. Upravo se zato Savezno vijeće (Bundesrat u njemačkom kontekstu, ili slično zakonodavno tijelo u drugim državama) bavi ovim pitanjem, nastojeći pronaći rješenja za rastuće probleme.\n\nGlavni predmet rasprave u Saveznom vijeću tiče se pooštravanja postojećih propisa ili uvođenja novih. Jedna od ključnih točaka je obvezna registracija e-romobila. Trenutno, u mnogim zemljama, e-romobili ne podliježu obveznoj registraciji poput automobila ili motocikala, što otežava praćenje prekršaja i utvrđivanje odgovornosti u slučaju nezgoda. Uvođenje registarskih tablica ili sličnih identifikacijskih oznaka olakšalo bi provedbu prometnih pravila i osiguralo veću sigurnost za sve sudionike u prometu.\n\nDrugi važan aspekt je regulacija parkiranja. E-romobili često završavaju razbacani po pješačkim zonama, blokirajući prolaze i stvarajući opasnost za pješake, pogotovo za osobe s invaliditetom. Prijedlozi uključuju uvođenje namjenskih parkirnih zona, strože kazne za nepropisno parkiranje, pa čak i tehnološka rješenja koja bi onemogućavala završetak vožnje izvan predviđenih zona.\n\nTakođer, razmatraju se i dobna ograničenja te obvezno nošenje kaciga. Dok su u nekim zemljama već na snazi dobna ograničenja, obvezno nošenje kaciga i dalje je predmet rasprave. Zagovornici tvrde da bi to značajno smanjilo broj ozljeda u nesrećama, dok se protivnici pozivaju na smanjenje privlačnosti e-romobila kao brzog i jednostavnog prijevoznog sredstva.\n\nOdluka Saveznog vijeća imat će dalekosežne posljedice ne samo za korisnike e-romobila, već i za pružatelje usluga najma te za same gradove. Cilj je pronaći ravnotežu između poticanja održivih oblika prijevoza i osiguravanja sigurnosti i reda u javnom prostoru. Ishod rasprave odredit će smjer razvoja mikro-mobilnosti u godinama koje dolaze.\n\n\"Potrebno je osigurati da e-romobili služe kao rješenje za urbanu mobilnost, a ne kao izvor novih problema. Odluka Saveznog vijeća ključna je za budućnost naših gradova.\" - izjavio je glasnogovornik udruge za sigurnost prometa.', 'uploads/politika_vijest3.jpg', 'Politika', 0),
(14, '2025-06-22', 'Imigracija: Trump želi više \"totalno briljantnih\" ljudi i manje ', 'Donald Trump ponovno izaziva kontroverze svojim izjavama o imigraciji...', 'Govoreći na skupu u Teksasu, Trump je izjavio kako bi Sjedinjene Američke Države trebale otvoriti vrata većem broju \"totalno briljantnih\" pojedinaca iz cijelog svijeta, umjesto da nastavljaju praksu \"lančane imigracije\", kojom članovi obitelji dobiju pravo na useljenje.\n\n\"Mi želimo najbolje od najboljih – znanstvenike, inženjere, genijalce. Ljude koji mogu pokrenuti kompanije, stvarati radna mjesta i gurati Ameriku naprijed,\" rekao je Trump. \"Ne trebamo svakog rođaka koji želi doći samo zato što netko već živi ovdje.\"\n\nNjegovi komentari predstavljaju nastavak politike koja naglašava imigraciju temeljenu na zaslugama, a ne obiteljskim vezama. Kritičari, međutim, tvrde da takav pristup zanemaruje humanitarnu dimenziju i važnost obiteljskog jedinstva.\n\nDemokrati su brzo reagirali, optuživši Trumpa za elitizam i ponovno poticanje podjela. \"Nijedna imigracijska politika ne smije zanemariti osnovna ljudska prava,\" poručila je kongresnica iz Kalifornije.\n\nI dok Trumpova izjava izaziva kontroverze, jasno je da će imigracija ostati ključna tema u nadolazećoj predizbornoj utrci.\n\n\"Mi ne možemo biti nacija snova ako ne znamo tko sanja – i zašto,\" rekao je analitičar CNN-a komentirajući sve veći jaz između dviju političkih vizija imigracije.', 'uploads/politika_vijest2.jpg', 'Politika', 0),
(15, '2025-06-22', 'Mršavljenje kroz sport i pravilnu prehranu - odgovori na često p', 'Stručnjaci odgovaraju na najčešća pitanja o zdravoj dijeti i vježbanju...', 'Želite smršaviti? Nema čarobnog štapića, ali postoji dokazana formula: kombinacija sporta i pravilne prehrane. Donosimo odgovore na najčešća pitanja koja si mnogi postavljaju na putu do željene težine.\n\nGubitak suvišnih kilograma često zvuči kao kompliciran proces, ali u svojoj srži svodi se na jednostavan princip: potrošiti više kalorija nego što se unese. Ipak, put do cilja često je ispunjen nedoumicama. Mnogi se pitaju koliko često trebaju vježbati, što jesti, i kako prevladati prepreke. Ključ je u informiranosti i dosljednosti.\n\nZa početak, važno je razumjeti ulogu kalorijskog deficita. Ne radi se o izgladnjivanju, već o pametnom odabiru namirnica koje vas drže sitima, a pritom pružaju potrebne nutrijente. Fokusirajte se na cjelovite, neprerađene namirnice – voće, povrće, cjelovite žitarice, nemasne proteine i zdrave masti. Izbjegavajte prazne kalorije iz slatkiša, grickalica i gaziranih pića.\n\nKada je riječ o fizičkoj aktivnosti, kombinacija je dobitna. Kardio vježbe poput trčanja, bicikliranja ili plivanja sagorijevaju kalorije tijekom samog treninga, dok trening snage (s utezima ili vlastitom težinom) gradi mišićnu masu. Više mišića znači veća potrošnja kalorija u mirovanju, što značajno ubrzava proces mršavljenja. Ciljajte na najmanje 150 minuta umjerene kardio aktivnosti tjedno uz 2-3 treninga snage.\n\nJedno od često postavljanih pitanja je i uloga doručka. Iako je doručak važan za mnoge, nije nužno \"najvažniji\" obrok dana za mršavljenje. Važnija je ukupna kvaliteta i količina hrane koju unosite tijekom cijelog dana. Slušajte svoje tijelo i jedite kada ste gladni, fokusirajući se na nutritivno bogate obroke.\n\nTijekom procesa mršavljenja često se javlja \"plato\", razdoblje kada se težina prestaje smanjivati. To je normalno i često znači da se vaše tijelo prilagodilo novom režimu. Kada se to dogodi, razmislite o blagoj promjeni kalorijskog unosa, povećanju intenziteta vježbanja ili uvođenju novih aktivnosti. Dovoljno sna i smanjenje stresa također igraju ključnu ulogu u održavanju metabolizma.\n\nZdrav i održiv gubitak težine obično iznosi 0,5 do 1 kilogram tjedno. Brži gubitak često je nezdrav i može dovesti do gubitka mišićne mase, a ne masti, te povećati rizik od takozvanog \"jo-jo efekta\". Ključ uspjeha leži u dugoročnim promjenama životnog stila, a ne u brzim dijetama.\n\n\"Mršavljenje je maraton, a ne sprint. Upornost i informiranost vode do trajnih rezultata i boljeg zdravlja.\" - citat stručnjaka za fitness i prehranu.', 'uploads/zdravlje_vijest1.jpg', 'Zdravlje', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `vijesti`
--
ALTER TABLE `vijesti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vijesti`
--
ALTER TABLE `vijesti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
