# Come collaborare al progetto

 È necessario conoscere i linguaggi di programmazione `HTML`, `PHP` e `SQL` (per database `MySQL`), mentre sono richieste le basi dei linguaggi `JS` (Javascript) e `CSS`
 
 Si raccomanda di utilizzare una buona IDE di programmazione come Phpstorm di Jetbrains. Per ottenere quest'ultima in modo completamente legale e gratuito è possibile ottenere una licenza studente <a href="https://www.jetbrains.com/student/" alt="Ottieni una licenza studente gratuita per i prodotti Jetbrains!">QUI</a>.
 
Prima di continuare, ti consiglio di leggere il [Glossario di GitHub](#Glossario).

Dunque, come cloniamo il nostro progetto?
## A. Clonare il repository
### A1. Clonare il repository da PHPStorm
#### 1. Installare Git
Dopo aver installato PHPStorm, installare Git. Git è un software di controllo versione distribuito utilizzabile da interfaccia a riga di comando.
Per poter installare Git prelevare la versione più recente da [QUI](https://git-scm.com/downloads) e seguire le istruzioni dell'installer (non spuntare nessuna opzione se non se ne conoscono gli effetti)
#### 2. Importare il repository da PHPStorm
1. Una volta aperto PHPStorm premere su `Configure` e, una volta aperte le impostazioni, premere su` Version Control`.
2. Cliccare su `GitHub`. Premere sul pulsante `Create API Token` e inserire le proprie credenziali.
3. Fatto ciò, chiudere le impostazioni e premere su `Checkout from Version Control`.
4. Inserire questo indirizzo nel campo URL: https://github.com/maicol07/school_life_diary_pc.git mentre nel campo directory inserire una cartella a piacimento dove deve essere clonato il repository
5. Attendere la fine della clonazione...
6. Una volta che si è aperta l'IDE vera e propria andare in `VCS` --> `Enable Version Control`. Scegliere `Git` dalla lista della finestra che appare e confermare.
7. Controllare che in `VCS` --> `Git` --> `Remotes`... ci sia l'URL immesso prima. Altrimenti inserirlo.
8. È ora possibile iniziare a lavorare sul progetto. Per mandare gli aggiornamenti al repo su GitHub premere su `VCS` --> `Commit`. Dopo aver controllato il tutto premere sulla freccia accanto al pulsante `Commit` e premere `Commit and push`.
9. Seguire le istruzioni finestra per finestra.
10. Continuare con il capitolo B di questa guida
### A2. Clonare un repository da GitHub per lavorare con altri programmi
**Saltare questa parte se si è già fatto la precedente con PHPStorm!!**

Si hanno due possibilità per chi intraprende questa strada:
#### 1. Scaricare il progetto
Nella pagina del repo di GitHub c'è un pulsante verde con scritto "**Download**", da qui è possibile scaricare un archivio zip contenente tutti i file del repository.
#### 2. Effettuare il `fork` di un repository
Premendo sul pulsante fork è possibile creare una copia del repo nel proprio account GitHub. Da qui è possibile eseguire tutte le modifiche e poi è possibile effettuare una pull request per mandarle al repo originale. Per maggiori informazioni vedi la voce al glossario sotto.
#### 3. Creare una nuova branch all'interno di un repository
Tramite il menu a discesa branch nella pagina del repo su Github è possibile creare una nuova branch, su cui lavorare mantenendo le modifiche separate dal ramo originale. Per maggiori informazioni vedi le voci nel glossario sottostante.
## B. Come installare l'interprete PHP
Per installare l'interprete PHP, il modo più semplice è installare il local server XAMPP.
### B1. Installazione di XAMPP
Per installare XAMPP occorre scaricare il setup da [QUI](https://www.apachefriends.org/it/download.html) (scaricare la versione più recente) e seguire le istruzioni dell'installer.
#### Provare ad aprire il panello di controllo di XAMPP
Provare ad aprire il pannello di controllo di XAMPP dal menu start o dall'icona sul desktop. Provare ad avviare i servizi Apache e MySQL.
In caso di errori fare riferimento alle [FAQ di XAMPP](https://www.apachefriends.org/faq_windows.html) o alla [Community di XAMPP (Apache Friends)](https://community.apachefriends.org/f/viewforum.php?f=34&sid=426fed9b82a2a9f63bcd31909036773d). 
### B2. Configurare XAMPP in PHPStorm
Se il passaggio precedente non ha dato errori o gli errori sono stati risolti, è possibile continuare con la configurazione di XAMPP in PHPStorm.

1. Aprire le impostazioni di XAMPP (File → Settings o scorciatoia da tastiera `CTRL + ALT + S`)
2. Navigare in `Build, Execution, Deployment` → Deployment  e aggiungere una nuova voce con nome `XAMPP` e tipo `Local or mounted folder`. Salvare.
3. Impostare i seguenti parametri:

    - Scheda `Connection`, parametro `Folder`: Immettere la cartella di installazione di XAMPP seguita da `\htdocs`. Esempio, se la cartella di installazione di XAMPP è quella predefinita (`C:\xampp`), allora il valore da immettere sarà: `C:\xampp\htdocs`
    - Scheda `Mappings`, parametro `Deployment path`: Immettere la cartella, anche non esistente, dentro quella del parametro impostato prima dove verranno inseriti i file del progetto in automatico ad ogni aggiornamento effettuato. Per esempio: `interrogazioni_programmate_webapp`
    - Scheda `Mappings`, parametro `Web path`: Ricopiare il valore del parametro precedente, subito dopo la `/`. Per esempio se il parametro precedente era `interrogazioni_programmate_webapp`, allora il parametro da inserire adesso sarà `/interrogazioni_programmate_webapp`
4. Impostare come server predefinito questa voce, utilizzando il pulsante nella scheda `Mappings` o quello nella barra degli strumenti.
5. Impostare l'upload dei file automatico, navigando nelle impostazioni in `Build, Execution, Deployment` → Deployment → Options e impostare il parametro `Upload changed files automatically to the default server` a `Always`.

Per maggiori informazioni sui primi 4 punti fare riferimento [a questa guida](https://confluence.jetbrains.com/display/PhpStorm/Deploying+PHP+applications+with+PhpStorm) di Jetbrains.

Invece, per l'ultimo punto fare riferimento [a questa guida](https://confluence.jetbrains.com/display/PhpStorm/Sync+changes+and+automatic+upload+to+a+deployment+server+in+PhpStorm) di Jetbrains.

## C. Operazioni con il repository GitHub
Il menu VCS (_Version Control System_) di PHPStorm permette di effettuare alcune operazioni con il repo di Github.
Iniziamo da quelle principali.
### C1. Effettuare il `commit` dei file
Per effettuare il `commit` dei file, ovvero l'invio dei file al server Git locale si usa il comando Commit nel menu VCS. 
Si aprirà questa finestra:
<img src="https://image.ibb.co/mJguAJ/immagine.png" alt="Finestra commit">

Consiglio di eseguire un singolo `commit` per ogni file, o per più file solo in caso di modifiche simili.

È possibile selezionare i file da includere nel commit, il nome dell'autore e di ottimizzare e riformattare il codice (opzioni a sinistra).
Nella prima sezione del menu laterale possiamo trovare due opzioni interessanti:
#### Amend Commit
L'opzione `Amend Commit` può sostituire il precedente commit con quello attuale (utile se si è eseguito un commit che causava malfunzionamenti o bug nel software).
#### Sign-off Commit
L'opzione `Sign-off Commit` aggiungerà alla fine del messaggio del commit la propria firma con la seguente dicitura:
`Signed-off by [nome]`
### C2. Inviare tutti i commit effettuati al repo GitHub
Una volta effettuati tutti i commit al server Git locale, è possibile inviarli al repo di Github. Dal menu `VCS --> Git` selezionare l'opzione `Push`.
<img src="https://image.ibb.co/bEtwj8/immagine.png" alt="Finestra Push">
In alto a sinistra troviamo l'elenco di tutti i commit eseguiti dall'invio dell'ultimo `Push` (se eseguito). Sulla destra abbiamo invece l'elenco di tutti i file che verranno aggiunti/modificati/eliminati.
Per confermare premere il pulsante `Push`.

# Glossario
Qui trovi tutte le parole più comuni di GitHub.
### Blame
La caratteristica "Blame" in Git descrive l'ultima modifica a ciascuna riga di un file, che generalmente visualizza la revisione, l'autore e il tempo. Ciò è utile, ad esempio, per rintracciare quando una funzione è stata aggiunta, o quale commit ha portato a un particolare bug.
### Branch
Un branch, o ramo in italiano, è una versione parallela di un repository. È contenuto nel repository, ma non influisce sul ramo principale o `master` che consente di lavorare liberamente senza interrompere la versione "live". Quando hai apportato le modifiche che desideri apportare, puoi unire nuovamente il ramo al ramo principale per pubblicare le modifiche.
### Clonare
Un clone è una copia di un repository che risiede sul tuo computer anziché sul server di un sito Web da qualche parte, o l'atto di realizzarlo. Con il tuo clone puoi modificare i file nel tuo editor preferito e utilizzare Git per tenere traccia delle tue modifiche senza dover essere online. È, tuttavia, connesso alla versione remota in modo che le modifiche possano essere sincronizzate tra i due. Puoi inviare le modifiche locali al repo remoto per mantenerle sincronizzate quando sei online.
### Collaboratore
Un collaboratore è una persona con accesso in lettura e scrittura a un repository che è stato invitato a contribuire dal proprietario del repository.
### Commit
Un commit, o "revisione", è una modifica individuale a un file (o una serie di file). È come quando salvi un file, tranne che con Git, ogni volta che lo salvi viene creato un ID univoco (a.k.a. "SHA" o "hash") che ti consente di tenere traccia di quali modifiche sono state apportate quando e da chi. I commit solitamente contengono un messaggio di commit che è una breve descrizione di quali modifiche sono state apportate.
### Contributore
Un contributore è qualcuno che ha contribuito a un progetto richiedendo la fusione di una richiesta pull ma non l'accesso di un collaboratore.
### Diff
Un diff è la differenza tra le modifiche tra due commit o le modifiche salvate. Il diff descriverà visivamente cosa è stato aggiunto o rimosso da un file dal suo ultimo commit.
### Fetch
Il fetching (o recupero) si riferisce all'ottenere le ultime modifiche da un repository online senza unirle. Una volta che queste modifiche sono state recuperate, è possibile confrontarle con le copie locali (il codice che risiede sul computer locale).
### Fork
Un fork è una copia personale del repository di un altro utente che risiede sul tuo account. I fork consentono di apportare liberamente modifiche a un progetto senza alterare l'originale. I fork rimangono attaccate all'originale, permettendoti di inviare una richiesta di pull (_pull request_) all'autore dell'originale per aggiornarle con le tue modifiche. È inoltre possibile mantenere aggiornato il fork applicando gli aggiornamenti dell'originale.
### Git
Git è un programma open source per il monitoraggio delle modifiche nei file di testo. È stato scritto dall'autore del sistema operativo Linux ed è la tecnologia di base su cui si basa GitHub, l'interfaccia sociale e utente.
### Issue
I problemi (issues) sono miglioramenti suggeriti, attività o domande relative al repository. I problemi possono essere creati da chiunque (per archivi pubblici) e sono moderati dai collaboratori del repository. Ogni problema contiene il proprio forum di discussione, può essere etichettato e assegnato a un utente.
### Markdown
Markdown è un semplice formato di file semantico, non troppo diverso da .doc, .rtf e .txt. Markdown rende facile anche a chi non ha uno sfondo di pubblicazione sul web di scrivere un testo (anche formattato con link, elenchi, elenchi puntati, ecc.) e visualizzarlo come un sito web. GitHub supporta Markdown e tu puoi [imparare la semantica](https://help.github.com/categories/writing-on-github/).
### Merge
L'unione prende le modifiche da un ramo (nello stesso repository o da un fork) e le applica a un altro. Ciò accade spesso come richiesta pull (che può essere pensata come una richiesta di unione) o tramite la riga di comando. Un'unione può essere eseguita automaticamente tramite una richiesta di pull tramite l'interfaccia web GitHub se non ci sono cambiamenti in conflitto, o può sempre essere fatta tramite la riga di comando. Per ulteriori informazioni, consultare ["Unione di una richiesta di pull"](https://help.github.com/articles/merging-a-pull-request).
### Open source
Il software open source è un software che può essere [liberamente utilizzato, modificato e condiviso (in forma modificata e non modificata) da chiunque](http://opensource.org/definition). Oggi il concetto di "open source" è spesso esteso al di là del software, per rappresentare una filosofia di collaborazione in cui i materiali di lavoro sono resi disponibili online per chiunque voglia effettuare un fork, modificare, discutere e contribuire.
Per ulteriori informazioni sull'argomento open source, in particolare su come creare e far crescere un progetto open source, abbiamo creato [guide open source](https://opensource.guide/) che ti aiuteranno a promuovere una sana community open source.
### Organizzazioni
Le organizzazioni sono account condivisi in cui aziende e progetti open source possono collaborare su più progetti contemporaneamente. I proprietari e gli amministratori possono gestire l'accesso dei membri ai dati e ai progetti dell'organizzazione con sofisticate funzioni di sicurezza e amministrative.
### Repository privato
I repository privati ​​sono repository che possono essere visualizzati o contribuiti solo dal loro creatore e dai collaboratori specificati dal creatore.
### Pull
Pull si riferisce a quando stai recuperando le modifiche e unendole. Ad esempio, se qualcuno ha modificato il file remoto su cui stai lavorando, vorrai inserire tali modifiche nella tua copia locale in modo che sia aggiornato.
### Pull request (in breve, PR)
Le richieste di pull vengono proposte come modifiche a un repository inviato da un utente e accettato o rifiutato dai collaboratori di un repository. Come i problemi, le richieste pull hanno ciascuna un proprio forum di discussione. Per ulteriori informazioni, consultare "[Informazioni sulle richieste di pull"](https://help.github.com/articles/about-pull-requests).
### Push
Il push si riferisce all'invio delle modifiche apportate a un repository remoto, ad esempio un repository ospitato su GitHub. Ad esempio, se si modifica qualcosa a livello locale, si desidera quindi spingere tali modifiche in modo che altri possano accedervi.
### Remoto (`Remote`, in inglese)
Questa è la versione di qualcosa che è ospitato su un server, molto probabilmente GitHub. Può essere collegato a cloni locali in modo che le modifiche possano essere sincronizzate.
### Repository (in breve, repo)
Un repository è l'elemento più basilare di GitHub. Sono più facili da immaginare come cartella di un progetto. Un repository contiene tutti i file di progetto (inclusa la documentazione) e memorizza la cronologia delle revisioni di ogni file. I repository possono avere più collaboratori e possono essere pubblici o privati.
### Chiave SSH
Le chiavi SSH sono un modo per identificarti in un server online, utilizzando un messaggio crittografato. È come se il tuo computer avesse la sua password univoca per un altro servizio. GitHub utilizza le chiavi SSH per trasferire in sicurezza le informazioni sul tuo computer.
## Squadre (Team)
I team sono gruppi di membri dell'organizzazione che riflettono la struttura dell'azienda o del gruppo con autorizzazioni e menzioni di accesso a cascata.
### Upstream
Quando si parla di un ramo o di una biforcazione (un fork), il ramo primario del repository originale viene spesso definito "upstream" ("a monte"), poiché questo è il punto principale da cui provengono le altre modifiche. Il ramo / fork su cui stai lavorando viene quindi chiamato "downstream".
### Utente
Gli utenti sono account GitHub personali. Ogni utente ha un profilo personale e può possedere più repository, pubblici o privati. Possono creare o essere invitati a unirsi alle organizzazioni o collaborare al repository di un altro utente.

_Adattato da [GitHub Glossary](https://help.github.com/articles/github-glossary/)_