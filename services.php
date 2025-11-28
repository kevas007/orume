<?php
/**
 * ============================================
 * PAGE SERVICES - FRONTEND PUBLIC
 * ============================================
 *
 * Cette page présente le détail des offres Orüme :
 * hero inspiré de la maquette, expertises, domaines,
 * visuel immersif et statistiques clés.
 *
 * @package Orüme
 * @version 1.0.0
 */
$pageClass = 'service-page';
?>
<!DOCTYPE html>
<html lang="fr">
<?php include 'partials/head.php'; ?>

<!-- HERO SECTION DÉDIÉE -->
</header>
<!-- HERO SECTION SERVICE -->
<section class="contact-hero service-hero" aria-labelledby="services-hero-title">
    <div class="contact-hero-content">
        <h1 id="services-hero-title">Nos services</h1>
        <p>Nous adaptons nos méthodologies à chaque besoin afin d'offrir des livrables clairs, performants et alignés sur vos objectifs.</p>
    </div>
</section>

<!-- SECTION 2 : NOS EXPERTISES -->
<section class="expertises-section" aria-labelledby="expertises-title">
    <div class="expertises-layout">
        <figure class="expertises-visual" aria-hidden="true">
            <span class="expertises-visual__circle"></span>
            <img src="assets/img/longue-vue-mascote.png" alt="Mascotte Orüme explorant avec une longue-vue">
        </figure>
        <div class="expertises-content">
            <p class="expertises-eyebrow">Nos expertises</p>
            <h2 id="expertises-title">Nos expertises</h2>
            <p>
                Spécialisée en UX/UI design, design graphique, shooting produit, référencement, développement de sites
                portfolio et création d'identité visuelle, notre équipe conçoit des expériences modernes et percutantes.
            </p>
            <p>
                Nous imaginons des univers graphiques cohérents pour aider les marques à mieux se présenter, se démarquer
                et attirer leur public.
            </p>
        </div>
    </div>
</section>

<!-- SECTION 3 : DOMAINES D'EXPERTISE -->
<section class="domains-section" aria-labelledby="domains-title">
    <div class="domains-header">
        <p class="domains-eyebrow">Domaines d'expertises</p>
        <h3 id="domains-title">Des solutions sur mesure</h3>
        <p>
           
        </p>
    </div>
    <div class="domains-grid">
        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <rect x="8" y="12" width="48" height="32" rx="4" ry="4" fill="none" stroke="#0b4f34" stroke-width="3.5"/>
                    <rect x="20" y="48" width="24" height="6" rx="2" fill="#0b4f34"/>
                    <text x="32" y="33" text-anchor="middle" font-size="12" fill="#f7a728" font-family="Arial, sans-serif">WEB</text>
                </svg>
            </div>
            <h4>Design UX/UI</h4>
            <p>Consultant sur tout projet digital, nous construisons des parcours fluides et accessibles.</p>
        </article>

        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <circle cx="32" cy="30" r="14" fill="none" stroke="#0b4f34" stroke-width="3.5"/>
                    <rect x="8" y="14" width="48" height="28" rx="4" ry="4" fill="none" stroke="#0b4f34" stroke-width="3"/>
                    <circle cx="44" cy="24" r="4" fill="#f7a728"/>
                    <rect x="24" y="46" width="16" height="6" rx="2" fill="#0b4f34"/>
                </svg>
            </div>
            <h4>Shooting Produit</h4>
            <p>Valorisation des produits en photo ou vidéo avec direction artistique dédiée.</p>
        </article>

        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <circle cx="26" cy="26" r="12" fill="none" stroke="#0b4f34" stroke-width="3"/>
                    <line x1="36" y1="36" x2="52" y2="52" stroke="#0b4f34" stroke-width="4" stroke-linecap="round"/>
                    <text x="26" y="30" text-anchor="middle" font-size="12" fill="#f7a728" font-family="Arial, sans-serif">SEO</text>
                </svg>
            </div>
            <h4>Référencement</h4>
            <p>Optimisation SEO/SEA pour faire grandir la visibilité organique et payante.</p>
        </article>

        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <rect x="10" y="14" width="44" height="36" rx="4" ry="4" fill="none" stroke="#0b4f34" stroke-width="3"/>
                    <polygon points="18,24 28,34 21,44 37,30 46,38 46,20" fill="#f7a728"/>
                </svg>
            </div>
            <h4>Identité Visuelle</h4>
            <p>Création et gestion d’image de marque cohérente sur l’ensemble de vos supports.</p>
        </article>

        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <rect x="12" y="18" width="40" height="28" rx="4" fill="none" stroke="#0b4f34" stroke-width="3"/>
                    <rect x="18" y="24" width="12" height="16" rx="2" fill="#0b4f34"/>
                    <rect x="34" y="24" width="12" height="8" rx="2" fill="#f7a728"/>
                    <rect x="34" y="34" width="12" height="6" rx="2" fill="#0b4f34"/>
                </svg>
            </div>
            <h4>Site Vitrine</h4>
            <p>Développement et intégration de sites vitrines performants et administrables.</p>
        </article>

        <article class="domain-card">
            <div class="domain-icon" aria-hidden="true">
                <svg viewBox="0 0 64 64" role="presentation">
                    <path d="M20 48 L28 16 L36 16 L44 48" fill="none" stroke="#0b4f34" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="32" cy="16" r="4" fill="#f7a728"/>
                    <rect x="18" y="48" width="28" height="6" rx="3" fill="#0b4f34"/>
                </svg>
            </div>
            <h4>Design Graphique</h4>
            <p>Conception de supports print & social media avec une direction artistique singulière.</p>
        </article>
    </div>
</section>

<!-- SECTION 4 : VISUEL LARGE -->
<section class="services-banner" aria-label="Rencontre avec nos partenaires">
    <div class="services-banner__image" role="presentation"></div>
</section>

<!-- SECTION 5 : STATISTIQUES & VALEURS -->
<section class="services-stats" aria-labelledby="stats-title">
    <div class="services-stats__layout">
        <div class="mascot-illustration mascot-illustration--idea" role="img" aria-label="Mascotte Orüme présentant les résultats"></div>
        <div class="stats-content">
            <p class="stats-eyebrow">Impact mesurable</p>
            <h3 id="stats-title">Des projets livrés avec rigueur</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">+200</span>
                    <p>Affiches réalisées</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">+130</span>
                    <p>Sites vitrines</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">+250</span>
                    <p>Photos produit</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">+10</span>
                    <p>Experts dédiés</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">+50</span>
                    <p>Partenaires fidèles</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">+400</span>
                    <p>Projets aboutis</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-values" aria-label="Les valeurs Orüme">
    <div class="services-values__track">
        <span>Flexibilité</span>
        <span class="star">*</span>
        <span>Créativité</span>
        <span class="star">*</span>
        <span>Professionalisme</span>
        <span class="star">*</span>
        <span>Rigueur</span>
        <span class="star">*</span>
        <span>Flexibilité</span>
        <span class="star">*</span>
        <span>Créativité</span>
        <span class="star">*</span>
        <span>Professionalisme</span>
        <span class="star">*</span>
        <span>Rigueur</span>
    </div>
</section>

<?php
      include 'partials/footer.php';
?>

</body>
</html>

