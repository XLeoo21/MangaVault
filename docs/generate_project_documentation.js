import fs from "node:fs";
import path from "node:path";
import { execSync } from "node:child_process";

const root = process.cwd();
const docsDir = path.join(root, "docs");
const htmlOutput = path.join(docsDir, "MangaVault_Memoria_Tecnica_Completa_CA.html");
const pdfOutput = path.join(docsDir, "MangaVault_Memoria_Tecnica_Completa_CA.pdf");

const includeTargets = [
    ".env.example",
    "README.md",
    "composer.json",
    "package.json",
    "vite.config.js",
    "tailwind.config.js",
    "postcss.config.js",
    "app",
    "database/factories",
    "database/migrations",
    "database/seeders",
    "resources/views",
    "routes",
    "lang",
    "tests",
];

const skipFiles = new Set([
    "database/database.sqlite",
]);

const categoryOrder = [
    "Arrel i configuracio",
    "Rutes",
    "Models i permisos",
    "Controladors",
    "Requests i validacio",
    "Components PHP",
    "Base de dades",
    "Vistes i layouts",
    "Traduccions",
    "Tests",
    "Altres fitxers",
];

function normalize(p) {
    return p.replace(/\\/g, "/");
}

function htmlEscape(value) {
    return value
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

function readUtf8(filePath) {
    return fs.readFileSync(filePath, "utf8");
}

function getAllFiles(target) {
    const absoluteTarget = path.join(root, target);
    if (!fs.existsSync(absoluteTarget)) {
        return [];
    }

    const stat = fs.statSync(absoluteTarget);
    if (stat.isFile()) {
        return [absoluteTarget];
    }

    const entries = [];

    function walk(current) {
        for (const entry of fs.readdirSync(current, { withFileTypes: true })) {
            const absolutePath = path.join(current, entry.name);
            const relativePath = normalize(path.relative(root, absolutePath));

            if (entry.isDirectory()) {
                walk(absolutePath);
                continue;
            }

            if (skipFiles.has(relativePath)) {
                continue;
            }

            entries.push(absolutePath);
        }
    }

    walk(absoluteTarget);
    return entries;
}

function relativeFileList() {
    const allFiles = includeTargets
        .flatMap(getAllFiles)
        .map((filePath) => normalize(path.relative(root, filePath)))
        .filter((filePath) => !skipFiles.has(filePath));

    return [...new Set(allFiles)].sort((a, b) => a.localeCompare(b));
}

function explainFile(relativePath) {
    const lower = relativePath.toLowerCase();

    const specific = {
        ".env.example": "Plantilla d'entorn del projecte. Defineix els valors base de configuracio, especialment la connexio SQLite, la localitzacio catalana i les variables necessaries per arrencar Laravel.",
        "README.md": "Document de presentacio general del projecte. Serveix com a resum inicial per a qualsevol persona que obri el repositori.",
        "composer.json": "Fitxer principal de dependències PHP. Declara Laravel 13, Breeze, PHPUnit i els scripts de Composer per instal.lar, provar i preparar l'aplicacio.",
        "package.json": "Fitxer principal de dependències JavaScript. Defineix Vite, Tailwind, Alpine i els scripts de desenvolupament i build del frontend.",
        "vite.config.js": "Configuracio de Vite. Connecta el bundler amb Laravel i defineix els punts d'entrada CSS i JavaScript.",
        "tailwind.config.js": "Configuracio de Tailwind CSS. Marca els fitxers que Tailwind ha d'escanejar per generar les classes necessaries.",
        "postcss.config.js": "Configuracio de PostCSS. Complementa Tailwind dins del pipeline del frontend.",
        "routes/web.php": "Fitxer central de rutes web. Separa el cataleg public, la zona autenticada i la zona d'administracio.",
        "routes/auth.php": "Rutes estàndard de Breeze per al registre, login, logout, verificacio de correu i recuperacio de contrasenya.",
        "routes/console.php": "Punt d'entrada per a ordres programades o de consola definides pel projecte.",
    };

    if (specific[relativePath]) {
        return specific[relativePath];
    }

    if (lower.startsWith("app/http/controllers/auth/")) {
        return "Controlador de Breeze relacionat amb autenticacio o seguretat del compte. Gestiona un pas concret del flux d'acces, registre, verificacio o recuperacio.";
    }

    if (lower === "app/http/controllers/mangacontroller.php") {
        return "Controlador principal del cataleg de mangues. Implementa el CRUD, la carrega de relacions, les validacions i les comprovacions d'autoritzacio per als creadors i l'admin.";
    }

    if (lower === "app/http/controllers/genrecontroller.php") {
        return "Controlador d'administracio de generes. Permet llistar, crear, editar i eliminar generes, i mostrar els mangues relacionats amb cada genere.";
    }

    if (lower === "app/http/controllers/usercontroller.php") {
        return "Controlador d'administracio d'usuaris. Dona eines per crear comptes, actualitzar rols i gestionar els usuaris existents.";
    }

    if (lower === "app/http/controllers/collectioncontroller.php") {
        return "Controlador de la col.leccio personal. Gestiona la taula pivot manga_user i permet afegir, editar i treure mangues de la llista de lectura.";
    }

    if (lower === "app/http/controllers/profilecontroller.php") {
        return "Controlador del perfil d'usuari de Breeze. Gestiona l'edicio del perfil, el canvi de correu i l'eliminacio del compte.";
    }

    if (lower === "app/http/controllers/controller.php") {
        return "Classe base dels controladors. Carrega els traits d'autoritzacio i validacio que reutilitza la resta de controladors.";
    }

    if (lower.startsWith("app/http/controllers/")) {
        return "Controlador HTTP del projecte. Centralitza la logica d'una funcionalitat concreta i connecta les rutes amb els models i les vistes.";
    }

    if (lower.startsWith("app/http/requests/")) {
        return "Request personalitzat de Laravel. Centralitza regles de validacio i autoritzacio per mantenir els controladors mes nets.";
    }

    if (lower === "app/models/user.php") {
        return "Model d'usuari. Defineix els casts, la verificacio de correu i les relacions amb els mangues creats i la col.leccio personal.";
    }

    if (lower === "app/models/manga.php") {
        return "Model principal de manga. Representa cada fitxa del cataleg i defineix les relacions amb el creador, els generes i els usuaris que el tenen a la col.leccio.";
    }

    if (lower === "app/models/genre.php") {
        return "Model de genere. Enllaça els generes amb els mangues a traves de la pivot genre_manga.";
    }

    if (lower.startsWith("app/models/")) {
        return "Model Eloquent del projecte. Representa una taula o concepte de domini i les seves relacions.";
    }

    if (lower.startsWith("app/policies/")) {
        return "Policy de Laravel. Defineix qui pot veure, crear, editar o eliminar recursos segons el rol o la propietat.";
    }

    if (lower.startsWith("app/providers/")) {
        return "Provider de Laravel. Registra comportaments globals del framework, com gates o serveis compartits.";
    }

    if (lower.startsWith("app/view/components/")) {
        return "Classe PHP d'un component Blade. Serveix de pont entre Laravel i els layouts reutilitzables de la interfície.";
    }

    if (lower.startsWith("database/factories/")) {
        return "Factory de Laravel. Genera dades de prova consistents per als tests o per a l'entorn de desenvolupament.";
    }

    if (lower.startsWith("database/migrations/")) {
        return "Migracio de base de dades. Defineix l'estructura d'una taula o un canvi d'esquema del projecte.";
    }

    if (lower.startsWith("database/seeders/")) {
        return "Seeder de Laravel. Insereix dades inicials i comptes de prova per poder avaluar el projecte rapidament.";
    }

    if (lower.startsWith("resources/views/layouts/")) {
        return "Layout principal de Blade. Marca l'estructura comuna de la pagina, la navegacio i els missatges globals.";
    }

    if (lower.startsWith("resources/views/components/")) {
        return "Component Blade reutilitzable. Encapsula petites peces d'interficie com botons, inputs, enllacos o modals.";
    }

    if (lower.startsWith("resources/views/auth/")) {
        return "Vista de Breeze per autenticacio. Mostra formularis de login, registre, verificacio de correu o recuperacio de contrasenya.";
    }

    if (lower.startsWith("resources/views/profile/")) {
        return "Vista del perfil d'usuari. Permet editar dades del compte, canviar contrasenya o eliminar l'usuari.";
    }

    if (lower.startsWith("resources/views/mangas/")) {
        return "Vista del modul de mangues. Presenta el cataleg, els formularis de CRUD i el detall de cada manga.";
    }

    if (lower.startsWith("resources/views/genres/")) {
        return "Vista del modul de generes. Dona suport a la gestio administrativa i al llistat de mangues per genere.";
    }

    if (lower.startsWith("resources/views/users/")) {
        return "Vista del modul d'usuaris. Recull les pantalles d'administracio per llistar, crear i editar comptes.";
    }

    if (lower.startsWith("resources/views/collections/")) {
        return "Vista de la col.leccio personal. Mostra els mangues desats per l'usuari i el formulari per actualitzar el pivot.";
    }

    if (lower === "resources/views/dashboard.blade.php") {
        return "Pantalla resum del tauler autenticat. Mostra estadistiques rapides i enllacos directes a les funcionalitats mes importants.";
    }

    if (lower === "resources/views/welcome.blade.php") {
        return "Portada publica del projecte. Resumeix el valor de MangaVault, ensenya estadistiques del cataleg i destaca alguns mangues recents.";
    }

    if (lower.startsWith("resources/views/")) {
        return "Vista Blade del projecte. S'encarrega de renderitzar la part visual d'una funcionalitat o component.";
    }

    if (lower.startsWith("lang/ca/")) {
        return "Fitxer de traduccio de Laravel en catala. Centralitza missatges de validacio, autenticacio o paginacio.";
    }

    if (lower === "lang/ca.json") {
        return "Diccionari JSON de textos de Breeze i de la interfície. Traduix cadenes curtes que Laravel resol directament amb __().";
    }

    if (lower.startsWith("tests/feature/auth/")) {
        return "Test funcional de Breeze. Verifica el comportament real dels fluxos d'autenticacio del projecte.";
    }

    if (lower === "tests/feature/mangavaulttest.php") {
        return "Test funcional propi del projecte. Comprova permisos, CRUD principal i gestio de la col.leccio personal.";
    }

    if (lower.startsWith("tests/feature/")) {
        return "Test funcional de Laravel. Exercita rutes, formularis i respostes HTTP per assegurar que la funcionalitat visible treballa correctament.";
    }

    if (lower.startsWith("tests/unit/")) {
        return "Test unitari o exemple base de PHPUnit inclos al projecte.";
    }

    if (lower.startsWith("tests/")) {
        return "Fitxer de proves del projecte. Ajuda a validar el comportament de l'aplicacio després de canvis o refactors.";
    }

    return "Fitxer de suport del projecte. Forma part de l'estructura general i contribueix al funcionament o manteniment de MangaVault.";
}

function categoryForPath(relativePath) {
    const lower = relativePath.toLowerCase();

    if ([".env.example", "readme.md", "composer.json", "package.json", "vite.config.js", "tailwind.config.js", "postcss.config.js"].includes(lower)) {
        return "Arrel i configuracio";
    }

    if (lower.startsWith("routes/")) {
        return "Rutes";
    }

    if (lower.startsWith("app/models/") || lower.startsWith("app/policies/") || lower.startsWith("app/providers/")) {
        return "Models i permisos";
    }

    if (lower.startsWith("app/http/controllers/")) {
        return "Controladors";
    }

    if (lower.startsWith("app/http/requests/")) {
        return "Requests i validacio";
    }

    if (lower.startsWith("app/view/components/")) {
        return "Components PHP";
    }

    if (lower.startsWith("database/")) {
        return "Base de dades";
    }

    if (lower.startsWith("resources/views/")) {
        return "Vistes i layouts";
    }

    if (lower.startsWith("lang/")) {
        return "Traduccions";
    }

    if (lower.startsWith("tests/")) {
        return "Tests";
    }

    return "Altres fitxers";
}

function getTreeText(files) {
    return files.map((file) => `- ${file}`).join("\n");
}

function getProjectSummary() {
    return [
        "MangaVault es una aplicacio Laravel orientada a gestionar un cataleg comunitari de mangues. El projecte combina una zona publica per consultar el cataleg amb una zona autenticada on els usuaris poden crear mangues, seguir la seva lectura i administrar la seva col.leccio personal.",
        "L'aplicacio treballa principalment amb quatre peces: usuaris, mangues, generes i una col.leccio personal basada en la taula pivot manga_user. Aquesta pivot desa l'estat de lectura, la puntuacio i el capitol actual de cada usuari per a cada manga que vulgui seguir.",
        "A nivell funcional hi ha tres perfils principals: visitant, usuari autenticat i administrador. El visitant pot veure el cataleg i el detall dels mangues. L'usuari autenticat pot crear mangues i gestionar la seva col.leccio. L'administrador, a mes, te control complet sobre mangues, generes i usuaris.",
    ];
}

function chunkCodeLines(raw, linesPerChunk = 42) {
    const lines = raw.split(/\r?\n/);
    const chunks = [];

    for (let index = 0; index < lines.length; index += linesPerChunk) {
        chunks.push(lines.slice(index, index + linesPerChunk));
    }

    return chunks;
}

function getDataModelSummary() {
    return [
        "User: model d'autenticacio basat en Breeze. Pot crear molts mangues i tenir molts mangues a la seva col.leccio personal.",
        "Manga: fitxa principal del cataleg. Pertany a un usuari creador i pot estar relacionat amb molts generes i molts usuaris mitjancant pivots diferents.",
        "Genre: classifica els mangues. La relacio es many-to-many amb la taula genre_manga.",
        "manga_user: pivot de col.leccio personal. Desa status, rating i current_chapter, de manera que no es limita a ser una simple relacio d'enllac.",
    ];
}

function getPermissionSummary() {
    return [
        "Els visitants nomes poden veure la portada, el cataleg i el detall public dels mangues.",
        "Els usuaris autenticats poden crear mangues i gestionar exclusivament la seva propia col.leccio personal.",
        "Els usuaris normals nomes poden editar o eliminar mangues creats per ells mateixos.",
        "L'administrador te acces complet a tots els mangues i, a mes, es l'unic que pot accedir als CRUD de generes i usuaris.",
        "Les restriccions es reforcen amb la MangaPolicy i amb el gate global is_admin registrat a AppServiceProvider.",
    ];
}

function getRouteList() {
    return execSync("php artisan route:list", {
        cwd: root,
        encoding: "utf8",
        stdio: ["ignore", "pipe", "pipe"],
    });
}

function getDatabaseSnapshot() {
    try {
        const output = execSync(
            "@'\n" +
            "echo 'USERS='.App\\Models\\User::count().PHP_EOL;\n" +
            "echo 'MANGAS='.App\\Models\\Manga::count().PHP_EOL;\n" +
            "echo 'GENRES='.App\\Models\\Genre::count().PHP_EOL;\n" +
            "echo 'COLLECTION_ROWS='.Illuminate\\Support\\Facades\\DB::table('manga_user')->count().PHP_EOL;\n" +
            "foreach (App\\Models\\Manga::orderBy('id')->get() as $manga) { echo $manga->id.'|'.$manga->title.PHP_EOL; }\n" +
            "'@ | php artisan tinker",
            {
                cwd: root,
                encoding: "utf8",
                stdio: ["ignore", "pipe", "pipe"],
                shell: "powershell.exe",
            }
        );

        return output.trim();
    } catch {
        return "No s'ha pogut obtenir una instantania de la base de dades en el moment de generar la memoria.";
    }
}

function fileSection(relativePath) {
    const absolutePath = path.join(root, relativePath);
    const raw = readUtf8(absolutePath);
    const explanation = explainFile(relativePath);
    const lines = raw.split(/\r?\n/).length;
    const bytes = fs.statSync(absolutePath).size;
    const codeChunks = chunkCodeLines(raw);

    const chunksHtml = codeChunks
        .map((chunkLines, index) => {
            const escapedCode = htmlEscape(chunkLines.join("\n"));
            const blockTitle = codeChunks.length === 1
                ? "Codi complet"
                : `Bloc de codi ${index + 1} de ${codeChunks.length}`;

            return `
                <section class="code-chunk">
                    <p class="chunk-label">${htmlEscape(blockTitle)}</p>
                    <pre class="code-block">${escapedCode}</pre>
                </section>
            `;
        })
        .join("\n");

    return `
        <article class="file-card">
            <div class="file-header">
                <h3>${htmlEscape(relativePath)}</h3>
                <p><strong>Categoria:</strong> ${htmlEscape(categoryForPath(relativePath))}</p>
                <p><strong>Rol del fitxer:</strong> ${htmlEscape(explanation)}</p>
                <p><strong>Metadades:</strong> ${lines} linies | ${bytes} bytes</p>
            </div>
            ${chunksHtml}
        </article>
    `;
}

function sectionBlock(title, innerHtml, extraClass = "") {
    return `
        <section class="page ${extraClass}">
            <h2>${htmlEscape(title)}</h2>
            ${innerHtml}
        </section>
    `;
}

function buildHtml() {
    const files = relativeFileList();
    const grouped = new Map();

    for (const category of categoryOrder) {
        grouped.set(category, []);
    }

    for (const file of files) {
        const category = categoryForPath(file);
        if (!grouped.has(category)) {
            grouped.set(category, []);
        }
        grouped.get(category).push(file);
    }

    const routeList = getRouteList();
    const databaseSnapshot = getDatabaseSnapshot();
    const totalFiles = files.length;
    const generatedAt = new Date().toLocaleString("ca-ES", {
        dateStyle: "full",
        timeStyle: "medium",
    });

    const tocEntries = [
        "Introduccio general",
        "Tecnologies i configuracio base",
        "Estructura de carpetes i inventari",
        "Arquitectura funcional i model de dades",
        "Sistema de permisos i seguretat",
        "Rutes disponibles",
        "Instantania actual de la base de dades",
        "Cataleg complet de fitxers amb explicacio i codi",
    ];

    const fileCatalogHtml = [...grouped.entries()]
        .filter(([, categoryFiles]) => categoryFiles.length > 0)
        .map(([category, categoryFiles]) => {
            const filesHtml = categoryFiles.map(fileSection).join("\n");

            return `
                <section class="category-block page-break-inside-avoid">
                    <h2>${htmlEscape(category)}</h2>
                    <p class="category-count">Total de fitxers en aquesta categoria: ${categoryFiles.length}</p>
                    ${filesHtml}
                </section>
            `;
        })
        .join("\n");

    const summaryParagraphs = getProjectSummary().map((text) => `<p>${htmlEscape(text)}</p>`).join("\n");
    const modelItems = getDataModelSummary().map((text) => `<li>${htmlEscape(text)}</li>`).join("\n");
    const permissionItems = getPermissionSummary().map((text) => `<li>${htmlEscape(text)}</li>`).join("\n");

    return `<!doctype html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MangaVault - Memoria Tecnica Completa</title>
    <style>
        :root {
            --text: #172033;
            --muted: #52607a;
            --line: #d9deea;
            --bg: #f5f7fb;
            --card: #ffffff;
            --code-bg: #eef2f9;
            --accent: #9f1239;
            --accent-soft: #ffe4eb;
        }

        * { box-sizing: border-box; }

        html, body {
            margin: 0;
            padding: 0;
            background: white;
            color: var(--text);
            font-family: "Times New Roman", Georgia, serif;
            line-height: 1.55;
        }

        main {
            max-width: 920px;
            margin: 0 auto;
            padding: 24px 18px 48px;
        }

        .page {
            padding: 8px 10px;
        }

        .cover-box {
            margin-top: 48px;
            border: 2px solid var(--accent);
            border-radius: 12px;
            padding: 32px;
            background: linear-gradient(180deg, white 0%, #fff8fa 100%);
        }

        .cover-title {
            margin: 0;
            text-align: center;
            color: var(--accent);
            font-size: 34px;
        }

        .cover-subtitle {
            margin: 10px 0 0;
            text-align: center;
            font-size: 20px;
        }

        .cover-note {
            margin-top: 18px;
            text-align: center;
            color: var(--muted);
        }

        .cover-grid {
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid var(--line);
        }

        .cover-grid p {
            margin: 7px 0;
        }

        h1, h2, h3 {
            margin-top: 0;
            line-height: 1.25;
        }

        h2 {
            font-size: 24px;
            margin-top: 22px;
            margin-bottom: 10px;
            border-bottom: 1px solid var(--line);
            padding-bottom: 6px;
            color: #19243a;
        }

        h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        p {
            margin: 8px 0;
            text-align: justify;
        }

        ul, ol {
            margin: 8px 0 8px 24px;
        }

        li {
            margin: 6px 0;
        }

        .toc {
            border: 1px solid var(--line);
            border-radius: 10px;
            background: var(--bg);
            padding: 12px 16px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .highlight-box {
            border: 1px solid var(--line);
            border-left: 6px solid var(--accent);
            border-radius: 10px;
            padding: 14px 16px;
            background: #fffafb;
        }

        .toc-page {
            min-height: 100vh;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .meta-card {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px 14px;
            background: var(--card);
        }

        .meta-card strong {
            display: block;
            margin-bottom: 4px;
            color: #111827;
        }

        .tree,
        .route-block,
        .snapshot-block,
        .code-block {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
            font-family: "Cascadia Code", Consolas, "Courier New", monospace;
            font-size: 11px;
            line-height: 1.45;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 14px;
            background: var(--code-bg);
        }

        .file-card {
            margin: 20px 0 28px;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--card);
        }

        .file-card h3 {
            color: var(--accent);
        }

        .file-header,
        .highlight-box,
        .meta-card,
        .category-block,
        .code-chunk {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .file-header {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--line);
        }

        .file-header h3,
        h2,
        h3 {
            break-after: avoid;
            page-break-after: avoid;
        }

        .category-block {
            margin-top: 30px;
        }

        .category-count {
            color: var(--muted);
        }

        .chunk-label {
            margin: 0 0 6px;
            font-size: 12px;
            font-weight: bold;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .code-chunk + .code-chunk {
            margin-top: 12px;
        }

        .note {
            font-size: 13px;
            color: var(--muted);
        }

        .page-break {
            page-break-before: always;
        }

        .page-break-inside-avoid {
            page-break-inside: avoid;
        }

        @media print {
            main {
                max-width: none;
                padding: 0;
            }

            .page {
                padding: 0;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <main>
        <section class="page">
            <div class="cover-box">
                <h1 class="cover-title">MangaVault</h1>
                <p class="cover-subtitle">Memoria tecnica completa del projecte</p>
                <p class="cover-note">Document generat automaticament a partir del codi real del repositori.</p>
                <div class="cover-grid">
                    <p><strong>Projecte:</strong> Aplicacio Laravel de cataleg comunitari de manga</p>
                    <p><strong>Abast de la memoria:</strong> estructura, arquitectura, rutes, base de dades, permisos, vistes, traduccions, tests i codi complet dels fitxers propis del projecte</p>
                    <p><strong>Fitxers documentats:</strong> ${totalFiles}</p>
                    <p><strong>Generat el:</strong> ${htmlEscape(generatedAt)}</p>
                    <p><strong>Sortides:</strong> ${htmlEscape(normalize(path.relative(root, htmlOutput)))} i ${htmlEscape(normalize(path.relative(root, pdfOutput)))}</p>
                </div>
            </div>
        </section>

        ${sectionBlock("Introduccio general", `
            ${summaryParagraphs}
        `)}

        <div class="page-break"></div>

        ${sectionBlock("Index de continguts", `
            <div class="toc">
                <ol>
                    ${tocEntries.map((entry) => `<li>${htmlEscape(entry)}</li>`).join("\n")}
                </ol>
            </div>
        `, "toc-page")}

        <div class="page-break"></div>

        ${sectionBlock("Tecnologies i configuracio base", `
            <p>MangaVault es construeix sobre Laravel 13, PHP 8.3, Laravel Breeze per a l'autenticacio, Blade per a les vistes, Tailwind CSS per a l'estil i Vite com a eina de bundling del frontend. La memoria cobreix el codi propi del projecte i exclou dependencies externes generades com <code>vendor</code>, <code>node_modules</code>, la base de dades binaria i els build artifacts.</p>
            <div class="meta-grid">
                <div class="meta-card">
                    <strong>Backend</strong>
                    <span>Laravel 13, Eloquent ORM, Breeze, Gates i Policies</span>
                </div>
                <div class="meta-card">
                    <strong>Frontend</strong>
                    <span>Blade, Tailwind CSS, Alpine.js i Vite</span>
                </div>
                <div class="meta-card">
                    <strong>Base de dades</strong>
                    <span>SQLite configurada per defecte a l'entorn actual</span>
                </div>
                <div class="meta-card">
                    <strong>Qualitat</strong>
                    <span>PHPUnit, factories, seeders i rutes verificables amb Artisan</span>
                </div>
            </div>
            <p class="note">Els fitxers clau d'aquesta part son <code>composer.json</code>, <code>package.json</code>, <code>.env.example</code>, <code>vite.config.js</code>, <code>tailwind.config.js</code> i <code>postcss.config.js</code>.</p>
        `)}

        ${sectionBlock("Estructura de carpetes i inventari", `
            <p>La llista seguent mostra tots els fitxers del projecte que s'han inclos dins d'aquesta memoria. Serveix com a mapa global abans d'entrar a la lectura detallada del codi.</p>
            <pre class="tree">${htmlEscape(getTreeText(files))}</pre>
        `)}

        <div class="page-break"></div>

        ${sectionBlock("Arquitectura funcional i model de dades", `
            <p>El projecte gira al voltant de quatre peces de domini que es relacionen entre si per donar suport al cataleg public i a la col.leccio personal de lectura.</p>
            <ul>
                ${modelItems}
            </ul>
            <div class="highlight-box">
                <p><strong>Relacions principals:</strong></p>
                <p>User 1..N Manga | Manga N..N Genre | User N..N Manga mitjancant manga_user</p>
                <p>La pivot <code>manga_user</code> desa <code>status</code>, <code>rating</code> i <code>current_chapter</code>, cosa que la converteix en una relacio amb significat funcional propi.</p>
            </div>
        `)}

        ${sectionBlock("Sistema de permisos i seguretat", `
            <p>La seguretat es basa en una combinacio de middleware, gates, policy i comprovacions directes d'autoritzacio dins dels controladors. Aixo evita que un usuari normal manipuli recursos d'un altre usuari.</p>
            <ul>
                ${permissionItems}
            </ul>
            <p>La resta de fluxos sensibles, com el perfil, la contrasenya o la verificacio del correu, queden delegats als controladors i vistes de Breeze, que continuen dins del projecte i tambe es documenten en aquesta memoria.</p>
        `)}

        ${sectionBlock("Rutes disponibles", `
            <p>Aquest bloc es una copia directa del resultat de <code>php artisan route:list</code> en el moment de generar la memoria. Permet veure d'una ullada totes les rutes web i a quin controlador apunten.</p>
            <pre class="route-block">${htmlEscape(routeList)}</pre>
        `)}

        ${sectionBlock("Instantania actual de la base de dades", `
            <p>Per completar la documentacio, aquest apartat afegeix una foto rapida de l'estat actual de la base de dades de desenvolupament. Pot variar si s'executen nous seeders o si s'afegeixen mes mangues manualment.</p>
            <pre class="snapshot-block">${htmlEscape(databaseSnapshot)}</pre>
        `)}

        <div class="page-break"></div>

        ${sectionBlock("Cataleg complet de fitxers amb explicacio i codi", `
            <p>A partir d'aqui la memoria entra al detall fitxer per fitxer. Cada fitxer inclou tres parts: el seu cami relatiu, una explicacio del seu paper dins del projecte i el codi complet actual per poder estudiar exactament com esta implementat.</p>
            <p class="note">Si algun fitxer pertany a Breeze o a la infraestructura base de Laravel, es documenta igualment perque forma part del comportament final del projecte, pero cal recordar que son peces de suport sobre les quals s'ha adaptat MangaVault.</p>
            ${fileCatalogHtml}
        `)}
    </main>
</body>
</html>`;
}

const html = buildHtml();
fs.writeFileSync(htmlOutput, html, "utf8");

console.log(`HTML generat a ${htmlOutput}`);
console.log(`PDF previst a ${pdfOutput}`);
