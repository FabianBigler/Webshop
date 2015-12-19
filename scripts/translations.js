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
                'registrationSuccessful': 'Sie wurden erfolgreich registriert.'
            });

            $translateProvider.translations('fr', {
                'overview': 'FUCK'
            });

            $translateProvider.preferredLanguage('de');
            $translateProvider.useSanitizeValueStrategy(null);
        });

})(maribelle.translations || (maribelle.translations = {}));