<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Classe pour envoyer des emails via PHPMailer avec SMTP
 */
class Mailer
{

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
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function resetPasswordMail(string $destinataire, string $code, string $name): bool
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
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous avons reçu une demande pour réinitialiser votre mot de passe. Voici votre code : ' . htmlspecialchars($code) . '\n\nValable 15 minutes.' . '\n\nSi ce n’était pas vous, aucune action n’est nécessaire.\n\nCordialement,\nL’équipe de GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;

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
            return false;
        } finally {
            // Réinitialiser les destinataires
            $this->mailer->clearAddresses();
        }
    }

    public function emailChangeMail(string $destinataire, string $name, string $confirmationLink): bool
    {
        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Confirmez votre nouvelle adresse email';

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Confirmation de changement d\'adresse email</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . htmlspecialchars($name) . ',
                            <br><br>
                            Vous avez demandé à modifier l\'adresse email associée à votre compte. Veuillez confirmer cette nouvelle adresse en cliquant sur le bouton ci-dessous :
                        </p>
                        <p style="text-align: center; margin: 30px 0;">
                            <a href="' . htmlspecialchars($confirmationLink) . '" style="display: inline-block; padding: 12px 24px; background-color: #8bb0f0; color: #ffffff; font-size: 16px; font-weight: 500; text-decoration: none; border-radius: 6px; transition: background-color 0.2s ease;">
                                Confirmer l\'adresse email
                            </a>
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Ce lien est valide pendant 24 heures. Si vous n\'avez pas initié cette demande, veuillez ignorer cet email ou contacter notre support immédiatement à <a href="mailto:projetpfa2025.hmc@gmail.com" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">support@gaensaj.com</a>.
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
                            Vous avez reçu cet email suite à une demande de changement d\'adresse email.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nVous avez demandé à modifier l\'adresse email associée à votre compte. Veuillez confirmer cette nouvelle adresse en cliquant sur le lien suivant : ' . htmlspecialchars($confirmationLink) . '\n\nCe lien est valide pendant 24 heures. Si vous n\'avez pas initié cette demande, veuillez ignorer cet email ou contacter notre support à support@gaensaj.com.\n\nCordialement,\nL’équipe de GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            // Réinitialiser les destinataires
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
    public function emailChangedConfirmationMail(string $destinataire, string $name): bool
    {

        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Votre email a été modifié';

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
                            Nous vous informons que l\'email associé à votre compte a été modifié avec succès.
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
                            Vous avez reçu cet email car l\'email de votre compte a été modifié.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous vous informons que l\'email associé à votre compte a été modifié avec succès.\n\nSi vous avez effectué cette modification, aucune action n’est nécessaire. Si vous pensez que cette modification n’a pas été initiée par vous, veuillez contacter notre support immédiatement à support@gaensaj.com.\n\nCordialement,\nL’équipe de GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            // Réinitialiser les destinataires
            $this->mailer->clearAddresses();
        }
    }

    /**
     * Envoie un email pour signaler un problème
     *
     * @param string $destinataire Email du destinataire
     * @param string $name Nom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $message Message décrivant le problème
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function signalerProbleme(string $name, string $email, string $message, string $destinataire = "projetpfa2025.hmc@gmail.com"): bool
    {
        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir l'expéditeur (facultatif, si nécessaire)
            $this->mailer->setFrom($email, htmlspecialchars($name));

            // Définir le sujet
            $this->mailer->Subject = 'Signalement d\'un problème';

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Nouveau signalement de problème</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour Équipe GAENSAJ,
                            <br><br>
                            Un utilisateur a signalé un problème via votre plateforme.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Nom de l\'utilisateur :</strong> ' . htmlspecialchars($name) . '<br>
                            <strong>Email :</strong> ' . htmlspecialchars($email) . '<br>
                            <strong>Problème signalé :</strong><br>' . nl2br(htmlspecialchars($message)) . '
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Veuillez examiner ce problème et contacter l’utilisateur si nécessaire à l’adresse <a href="mailto:' . htmlspecialchars($email) . '" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">' . htmlspecialchars($email) . '</a>.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Cordialement,<br>
                            Système de signalement GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Cet email a été généré automatiquement suite au signalement d’un problème.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour Équipe GAENSAJ,\n\nUn utilisateur a signalé un problème via votre plateforme.\n\nNom de l\'utilisateur : ' . htmlspecialchars($name) . '\nEmail : ' . htmlspecialchars($email) . '\nProblème signalé :\n' . htmlspecialchars($message) . '\n\nVeuillez examiner ce problème et contacter l’utilisateur si nécessaire à l’adresse ' . htmlspecialchars($email) . '.\n\nCordialement,\nSystème de signalement GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        } finally {
            // Réinitialiser les destinataires
            $this->mailer->clearAddresses();
        }
    }

    /**
     * Envoie un email de confirmation avec les informations de connexion lors de la création d'un compte
     *
     * @param string $name Nom de l'utilisateur
     * @param string $email Email de l'utilisateur
     * @param string $username Nom d'utilisateur pour la connexion
     * @param string $password Mot de passe temporaire de l'utilisateur
     * @param string $destinataire Email du destinataire (généralement l'utilisateur lui-même)
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function confirmationAccount(string $name, string $email, string $username, string $password, string $destinataire): bool
    {
        try {
            // Ajouter le destinataire de l'email
            $this->mailer->addAddress($destinataire);

            // Définir le sujet de l'email
            $this->mailer->Subject = 'Bienvenue ! Vos informations de connexion';

            // Corps HTML de l'email avec un design moderne et responsive
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Bienvenue chez GAENSAJ !</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . htmlspecialchars($name) . ',
                            <br><br>
                            Votre compte a été créé avec succès sur la plateforme GAENSAJ. Voici vos informations de connexion :
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Nom d\'utilisateur :</strong> ' . htmlspecialchars($username) . '<br>
                            <strong>Mot de passe temporaire :</strong> ' . htmlspecialchars($password) . '<br>
                            <strong>Email :</strong> ' . htmlspecialchars($email) . '
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe dès votre première connexion en accédant à votre profil.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Vous pouvez vous connecter dès maintenant en cliquant ici : 
                            <a href="http://gaensaj.com/login" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">Se connecter</a>.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Cordialement,<br>
                            L\'équipe GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Cet email a été généré automatiquement suite à la création de votre compte.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut pour les clients email ne supportant pas le HTML
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nVotre compte a été créé avec succès sur la plateforme GAENSAJ.\n\nVoici vos informations de connexion :\n\nNom d\'utilisateur : ' . htmlspecialchars($username) . '\nMot de passe temporaire : ' . htmlspecialchars($password) . '\nEmail : ' . htmlspecialchars($email) . '\n\nPour des raisons de sécurité, nous vous recommandons de changer votre mot de passe dès votre première connexion en accédant à votre profil.\n\nVous pouvez vous connecter dès maintenant ici : https://gaensaj.com/login\n\nCordialement,\nL\'équipe GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur (email invalide, problème serveur SMTP, etc.), retourner false
            return false;
        } finally {
            // Réinitialiser les destinataires pour éviter des envois accidentels ultérieurs
            $this->mailer->clearAddresses();
        }
    }

    /**
     * Envoie un email de confirmation avec les informations de connexion pour un compte professeur
     *
     * @param string $name Nom du professeur
     * @param string $email Email du professeur
     * @param string $username Nom d'utilisateur pour la connexion
     * @param string $password Mot de passe temporaire du professeur
     * @param string $destinataire Email du destinataire (généralement le professeur lui-même)
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function confirmationProfessorAccount(string $name, string $email, string $username, string $password, string $destinataire): bool
    {
        try {
            // Ajouter le destinataire de l'email
            $this->mailer->addAddress($destinataire);

            // Définir le sujet de l'email
            $this->mailer->Subject = 'Bienvenue sur GAENSAJ - Votre compte professeur';

            // Corps HTML de l'email avec un design professionnel et adapté au contexte académique
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Bienvenue, Professeur !</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Cher(e) Professeur ' . htmlspecialchars($name) . ',
                            <br><br>
                            Nous sommes ravis de vous accueillir sur la plateforme GAENSAJ. Votre compte professeur a été créé avec succès. Voici vos informations de connexion :
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Nom d\'utilisateur :</strong> ' . htmlspecialchars($username) . '<br>
                            <strong>Mot de passe temporaire :</strong> ' . htmlspecialchars($password) . '<br>
                            <strong>Email :</strong> ' . htmlspecialchars($email) . '
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Pour garantir la sécurité de votre compte, nous vous recommandons vivement de modifier votre mot de passe temporaire lors de votre première connexion via les paramètres de votre profil.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Vous pouvez accéder à votre espace professeur dès maintenant pour commencer à gérer vos cours et interagir avec vos étudiants :
                            <a href="http://gaensaj.com/login" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">Accéder à mon espace</a>.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Respectueusement,<br>
                            L\'équipe administrative GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Cet email a été généré automatiquement suite à la création de votre compte professeur.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut pour les clients email ne supportant pas le HTML
            $this->mailer->AltBody = 'Cher(e) Professeur ' . htmlspecialchars($name) . ',\n\nNous sommes ravis de vous accueillir sur la plateforme GAENSAJ. Votre compte professeur a été créé avec succès.\n\nVoici vos informations de connexion :\n\nNom d\'utilisateur : ' . htmlspecialchars($username) . '\nMot de passe temporaire : ' . htmlspecialchars($password) . '\nEmail : ' . htmlspecialchars($email) . '\n\nPour garantir la sécurité de votre compte, nous vous recommandons vivement de modifier votre mot de passe temporaire lors de votre première connexion via les paramètres de votre profil.\n\nVous pouvez accéder à votre espace professeur ici : https://gaensaj.com/professor/login\n\nRespectueusement,\nL\'équipe administrative GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur (email invalide, problème serveur SMTP, etc.), retourner false
            return false;
        } finally {
            // Réinitialiser les destinataires pour éviter des envois accidentels ultérieurs
            $this->mailer->clearAddresses();
        }
    }

    /**
     * Envoie un email de confirmation avec les informations de connexion pour un compte administrateur
     *
     * @param string $name Nom de l'administrateur
     * @param string $email Email de l'administrateur
     * @param string $username Nom d'utilisateur pour la connexion
     * @param string $password Mot de passe temporaire de l'administrateur
     * @param string $destinataire Email du destinataire (généralement l'administrateur lui-même)
     * @return bool Retourne true si l'envoi réussit, false sinon
     * @throws Exception Si l'email est invalide ou l'envoi échoue
     */
    public function confirmationAdminAccount(string $name, string $email, string $username, string $password, string $destinataire): bool
    {
        try {
            // Ajouter le destinataire de l'email
            $this->mailer->addAddress($destinataire);

            // Définir le sujet de l'email
            $this->mailer->Subject = 'Creation de votre compte administrateur GAENSAJ';

            // Corps HTML de l'email avec un design professionnel et adapté au contexte administratif
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Bienvenue, Administrateur !</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Cher(e) ' . htmlspecialchars($name) . ',
                            <br><br>
                            Votre compte administrateur a été créé avec succès sur la plateforme GAENSAJ. Vous disposez désormais d’un accès privilégié pour gérer les utilisateurs, les cours et les paramètres système.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Nom d\'utilisateur :</strong> ' . htmlspecialchars($username) . '<br>
                            <strong>Mot de passe temporaire :</strong> ' . htmlspecialchars($password) . '<br>
                            <strong>Email :</strong> ' . htmlspecialchars($email) . '
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Pour des raisons de sécurité, il est impératif de modifier votre mot de passe temporaire lors de votre première connexion via les paramètres de votre compte administrateur.
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Accédez à votre tableau de bord administrateur pour commencer à configurer la plateforme :
                            <a href="http://gaensaj.com/login" style="color: #8bb0f0; text-decoration: underline; font-weight: 500;">Accéder au tableau de bord</a>.
                        </p>
                        <p style="color: #1c1d1e; font-size: 14px; line-height: 22px; margin: 20px 0 0; opacity: 0.8;">
                            Cordialement,<br>
                            Système GAENSAJ
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 15px; background-color: #363753;">
                        <p style="color: #ffffff; font-size: 12px; line-height: 18px; margin: 0; opacity: 0.7;">
                            Cet email a été généré automatiquement suite à la création de votre compte administrateur.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut pour les clients email ne supportant pas le HTML
            $this->mailer->AltBody = 'Cher(e) ' . htmlspecialchars($name) . ',\n\nVotre compte administrateur a été créé avec succès sur la plateforme GAENSAJ. Vous disposez désormais d’un accès privilégié pour gérer les utilisateurs, les cours et les paramètres système.\n\nVoici vos informations de connexion :\n\nNom d\'utilisateur : ' . htmlspecialchars($username) . '\nMot de passe temporaire : ' . htmlspecialchars($password) . '\nEmail : ' . htmlspecialchars($email) . '\n\nPour des raisons de sécurité, il est impératif de modifier votre mot de passe temporaire lors de votre première connexion via les paramètres de votre compte administrateur.\n\nAccédez à votre tableau de bord administrateur ici : https://gaensaj.com/admin/login\n\nCordialement,\nSystème GAENSAJ';

            // Envoyer l'email
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur (email invalide, problème serveur SMTP, etc.), retourner false
            return false;
        } finally {
            // Réinitialiser les destinataires pour éviter des envois accidentels ultérieurs
            $this->mailer->clearAddresses();
        }
    }

    public function absenceAlertMail(string $destinataire, string $name, string $courseName, string $nbreAbsence): bool
    {
        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Alerte du nombre d\'heure d\'abscence cumuler en absence en' . $courseName;

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Alerte d\'absence</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . mb_convert_encoding( htmlspecialchars($name), 'ISO-8859-1', 'UTF-8') . ',
                            <br><br>
                            Nous vous informons que vous avez accumuler un nombre total de ' . htmlspecialchars($nbreAbsence) . ' absences dans le cours suivant :
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Cours :</strong> ' . mb_convert_encoding( htmlspecialchars($courseName), 'ISO-8859-1', 'UTF-8'). '<br>
                            <strong>Nombre d\'heure d\'abscence : ' . htmlspecialchars($nbreAbsence) . '</strong>' .'
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                             Veuillez prendre les mesures nécessaires pour ne pas depasser le plafond d\'heure d\'abscence qui est de 8heures par module.
                             
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                             Rappel: en depassant le plafond d\'heure d\'abscence, vous ne pourrez pas ppassez l\'examen final du module et vous passerez directement à la session de rattrapage.
                             Veuillez contacter l\'administration pour régulariser votre situation.
                             
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
                            Vous avez reçu cet email suite à une alerte d\'absence.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous vous informons que vous avez accumuler un nombre total de ' . htmlspecialchars($nbreAbsence) . ' absences dans le cours suivant :\n\nCours : ' . htmlspecialchars($courseName) . '\nNombre d\'heure d\'abscence : ' . htmlspecialchars($nbreAbsence) . 
            '\n\nVeuillez prendre les mesures nécessaires pour ne pas depasser le plafond d\'heure d\'abscence qui est de 8heures par module.\n\nRappel: en depassant le plafond d\'heure d\'abscence, vous ne pourrez pas ppassez l\examen final du module et vous passerez directement à la session de rattrapage.\n\nCordialement,\nL’équipe de GAENSAJ';

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur (email invalide, problème serveur SMTP, etc.), retourner false
            return false;
        } finally {
            // Réinitialiser les destinataires pour éviter des envois accidentels ultérieurs
            $this->mailer->clearAddresses();
        }
    }

    public function alertEtudiantPrivee(string $destinataire, string $name, string $courseName): bool
    {
        try {
            // Définir le destinataire
            $this->mailer->addAddress($destinataire);

            // Définir le sujet
            $this->mailer->Subject = 'Situation Concernant la session d\'examen du module ' . $courseName;

            // Corps HTML
            $this->mailer->Body = '
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Arial, sans-serif; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px #1c1d1e33;">
                <tr>
                    <td align="center" bgcolor="#363753" style="padding: 30px 20px; background: linear-gradient(180deg, #363753 0%, #1c1d1e 100%);">
                        <h1 style="color: #ffffff; font-size: 26px; font-weight: 600; margin: 0; letter-spacing: 0.5px;">Suspension pour l\'examen</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 25px;">
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            Bonjour ' . mb_convert_encoding( htmlspecialchars($name), 'ISO-8859-1', 'UTF-8') . ',
                            <br><br>
                            Nous vous informons que vous avez depasser le plafond d\'heure d\'absences qui est de 8heures pour chaque module.\n
                            par consequent pour le cours suivant :
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                            <strong>Cours :</strong> ' . mb_convert_encoding( htmlspecialchars($courseName), 'ISO-8859-1', 'UTF-8'). '<br>
                            
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                             <strong>Vous etes suspendu pour la session d\'examen de ce module<//strong>
                             
                        </p>
                        <p style="color: #1c1d1e; font-size: 16px; line-height: 26px; margin: 0 0 20px;">
                             Donc ,de ce fait vous passerez directement a la session de ratrappage!!!!
                             
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
                            Vous avez reçu cet email suite à une alerte d\'absence.<br>
                            © 2025 GAENSAJ. Tous droits réservés.
                        </p>
                    </td>
                </tr>
            </table>';

            // Corps texte brut
            $this->mailer->AltBody = 'Bonjour ' . htmlspecialchars($name) . ',\n\nNous vous informons que vous avez depasser le nombre d\'heure absence accepter par module 
            \nVvous etes donc suspendu pour la session d\'examen dudit module.\n  vous passerez directement à la session de rattrapage.\n\nCordialement,\nL’équipe de GAENSAJ';

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            // En cas d'erreur (email invalide, problème serveur SMTP, etc.), retourner false
            return false;
        } finally {
            // Réinitialiser les destinataires pour éviter des envois accidentels ultérieurs
            $this->mailer->clearAddresses();
        }
    }

}