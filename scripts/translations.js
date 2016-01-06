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
				'deliveryAddress' : 'Lieferadresse',
				'invoiceAddress' : 'Rechnungsadresse',
				'completeOrder' : 'Bestellung abschliessen'
            });

            $translateProvider.translations('fr', {
                'overview': 'FUCK'
            });

            $translateProvider.preferredLanguage('de');
            $translateProvider.useSanitizeValueStrategy(null);
        });

})(maribelle.translations || (maribelle.translations = {}));