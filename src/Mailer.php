<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Classe pour envoyer des emails via PHPMailer avec SMTP
 */
class Mailer {

    /**
     * @var PHPMailer Instance de PHPMailer
     */
    private $mailer;

    /**
     * Constructeur qui initialise PHPMailer avec les paramètres SMTP
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'projetpfa2025.hmc@gmail.com';
        $this->mailer->Password = 'uoauxhuyjeiyjctj';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        $this->mailer->isHTML(true);
        $this->mailer->setFrom('projetpfa2025.hmc@gmail.com', 'GAENSAJ');
    }

    /**
     * Envoie un email de réinitialisation de mot de passe
     *
     * @param string $destinataire Email du destinataire
     * @param string $code Code de réinitialisation
     * @param string $name Nom de l'utilisateur
     * @param string $resetLink Lien de réinitialisation
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function resetPasswordMail(string $destinataire, string $code, string $name, string $resetLink =""): bool
    {
        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Votre code de reinitialisation de mot de passe';

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Demande de réinitialisation de mot de passe</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . htmlspecialchars($name) . ',
                            <br><br>
                            Nous avons reçu une demande pour réinitialiser le mot de passe associé à votre compte. Voici votre code personnel pour procéder :
                        </p>
                        <div style="text-align: center; margin: 25px 0; padding: 15px; background-color: #8bb0f0; border-radius: 6px; border: 1px solid #1c1d1e33;">
                            <span style="color: #1c1d1e; font-size: 28px; font-weight: bold; letter-spacing: 2px;">
                                ' . htmlspecialchars($code) . '
                            </span>
                        </div>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Pour continuer, utiliser ce code pour reinitialiser votre mot de passe. Il est valable pendant 15 minutes.
                            <br><br>
                            Si cette demande ne vient pas de vous, aucune action n’est nécessaire. Votre compte reste sécurisé.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Cordialement,<br>
                            L’équipe de GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Vous avez reçu cet email car une réinitialisation a été demandée pour votre compte.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous avons reçu une demande pour réinitialiser votre mot de passe. Voici votre code : ' . htmlspecialchars($code) . '\n\nValable 15 minutes. Visitez ce lien pour continuer : ' . $resetLink . '\n\nSi ce n’était pas vous, aucune action n’est nécessaire.\n\nCordialement,\nL’équipe de GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception('Échec de l’envoi de l’email : ' . $this->mailer->ErrorInfo);

        } finally {
            // Réinitialiser les destinataires pour éviter les envois multiples
            $this->mailer->clearAddresses();
        }
    }

    /**
     * Envoie un email de notification pour confirmer le changement de mot de passe
     *
     * @param string $destinataire Email du destinataire
     * @param string $name Nom de l'utilisateur
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function passwordChangedMail(string $destinataire, string $name): bool
    {

        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Votre mot de passe a été modifié';

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Mot de passe modifié</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . htmlspecialchars($name) . ',
                            <br><br>
                            Nous vous informons que le mot de passe associé à votre compte a été modifié avec succès.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Si vous avez effectué cette modification, aucune action n’est nécessaire. Si vous pensez que cette modification n’a pas été initiée par vous, veuillez contacter notre support immédiatement à <a href="mailto:projetpfa2025.hmc@gmail.com" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">support@gaensaj.com</a>.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Cordialement,<br>
                            L’équipe de GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Vous avez reçu cet email car le mot de passe de votre compte a été modifié.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous vous informons que le mot de passe associé à votre compte a été modifié avec succès.\n\nSi vous avez effectué cette modification, aucune action n’est nécessaire. Si vous pensez que cette modification n’a pas été initiée par vous, veuillez contacter notre support immédiatement à support@gaensaj.com.\n\nCordialement,\nL’équipe de GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new Exception('Échec de l’envoi de l’email : ' . $this->mailer->ErrorInfo);
        } finally {
            // Réinitialiser les destinataires
            $this->mailer->clearAddresses();
        }
    }
}