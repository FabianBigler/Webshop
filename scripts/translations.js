'use strict';

(function (translations) {

    angular.module('maribelle.translations', ['pascalprecht.translate'])
        .config(function ($translateProvider) {
            $translateProvider.translations('de', {
                'overview': 'Sortiment',
                'register': 'Registrieren',
                'email': 'Email',
                'street': 'Strasse',
                'postCodeCity': 'PLZ / Ort',
                'postCode': 'PLZ',
                'city': 'Ort',
                'password': 'Passwort',
                'passwordConfirm': 'Passwort bestätigen',
                'submitRegistration': 'Registrieren',
                'registrationSuccessful': 'Sie wurden erfolgreich registriert.',
                'login': 'Einloggen',
                'logout': 'Ausloggen',
                'SignedInAs': 'Eingeloggt als',
                'submitLogin': 'Einloggen',
                'loginSuccessful': 'Sie wurden erfolgreich eingeloggt.',
                'loginFailed': 'Fehlgeschlagen, überprüfen Sie Email und Passwort.',
				'name': 'Name',
				'amount': 'Anzahl',
				'price': 'Preis',
				'basket': 'Warenkorb',
				'detail': 'Detail',
				'ingredients': 'Zusammensetzung',	
				'addedItemToBasket': 'Artikel erfolgreich zum Warenkorb hinzugefügt!',
				'givenname': 'Vorname',
				'surname': 'Nachname',
				'total': 'Total',
				'deliveryAddress': 'Lieferadresse',
				'invoiceAddress': 'Rechnungsadresse',
				'completeOrder': 'Bestellung abschliessen',
                'fr': 'FR',
				'de': 'DE',
                'frLong': 'Französisch',
				'deLong': 'Deutsch'
            });

            $translateProvider.translations('fr', {
                'overview': 'Vue d\'ensemble',
                'register': 'Registre',
                'email': 'Émail',
                'street': 'Rue',
                'postCodeCity': 'NPA / Ville',
                'postCode': 'NPA',
                'city': 'Ville',
                'password': 'Mot de passe',
                'passwordConfirm': 'Confirmer mot de passe',
                'submitRegistration': 'Registre',
                'registrationSuccessful': 'Vous avez été enregistré avec succès.',
                'login': 'Login',
                'logout': 'Logout',
                'SignedInAs': 'Connecté en tant que',
                'submitLogin': 'Login',
                'loginSuccessful': 'Vous avez été connecté avec succès.',
                'loginFailed': 'Échec, consulter vos e-mail et mot de passe.',
				'name': 'Nom',
				'amount': 'Nombre',
				'price': 'Prix',
				'basket': 'Achats',
				'detail': 'Détail',
				'ingredients': 'Composition',	
				'addedItemToBasket': 'Article ajouté au panier avec succès!',
				'givenname': 'Prénom',
				'surname': 'Nom de famille',
				'total': 'Totalement',
				'deliveryAddress': 'Adresse de livraison',
				'invoiceAddress': 'Adresse de facturation',
				'completeOrder': 'Terminer la commande',
				'fr': 'FR',
				'de': 'DE',
				'frLong': 'Français',
				'deLong': 'Allemand'
            });

            $translateProvider.preferredLanguage('de');
            $translateProvider.useSanitizeValueStrategy(null);
        });

})(maribelle.translations || (maribelle.translations = {}));