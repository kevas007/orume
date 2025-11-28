/**
 * Fonction pour répondre à un message
 * Ouvre le client email par défaut avec l'email et le sujet pré-remplis
 */
function repondre(email, sujet) {
  // Encoder l'email et le sujet pour l'URL
  const emailEncoded = encodeURIComponent(email);
  const sujetEncoded = encodeURIComponent('Re: ' + sujet);
  
  // Créer le lien mailto
  const mailtoLink = `mailto:${emailEncoded}?subject=${sujetEncoded}`;
  
  // Ouvrir le client email
  window.location.href = mailtoLink;
}

