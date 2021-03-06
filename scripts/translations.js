'use strict';

(function (translations) {

    angular.module('maribelle.translations', ['pascalprecht.translate'])
        .config(function ($translateProvider) {
            $translateProvider.translations('DE', {
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
                'FR': 'Français',
                'DE': 'Deutsch',
                'EN': 'English',
                'orderCompleted': 'Die Bestellung wurde abgeschickt.',
                'profile': 'Profil',
                'previousOrders': 'Bisherige Bestellungen',
                'noOrdersJet': 'Bisher keine Bestellungen gesendet.',
                'orderOf': 'Bestellung vom',
                'linesInOrder': 'Produkt(e) bestellt'
            });
            
            $translateProvider.translations('EN', {
                'overview': 'Overview',
                'register': 'Register',
                'email': 'Email',
                'street': 'Street',
                'postCodeCity': 'Postcode / City',
                'postCode': 'Postcode',
                'city': 'City',
                'password': 'Password',
                'passwordConfirm': 'Password confirmation',
                'submitRegistration': 'Register',
                'registrationSuccessful': 'You have been successfully registered.',
                'login': 'Login',
                'logout': 'Logout',
                'SignedInAs': 'Signed in as',
                'submitLogin': 'Login',
                'loginSuccessful': 'Vous avez été connecté avec succès.',
                'loginFailed': 'Échec, consulter vos e-mail et mot de passe.',
                'name': 'Name',
                'amount': 'Amount',
                'price': 'Price',
                'basket': 'Basket',
                'detail': 'Detail',
                'ingredients': 'Ingredients',    
                'addedItemToBasket': 'Item successfully added to basket!',
                'givenname': 'Givenname',
                'surname': 'Surname',
                'total': 'Total',
                'deliveryAddress': 'Delivery address',
                'invoiceAddress': 'Invoice address',
                'completeOrder': 'Complete Order',
                'FR': 'Français',
                'DE': 'Deutsch',
                'EN': 'English',
                'orderCompleted': 'The order has been sent.',
                'profile': 'Profile',
                'previousOrders': 'Previous orders',
                'noOrdersJet': 'So far no orders sent.',
                'orderOf': 'Order from',
                'linesInOrder': 'ordered product(s)'
            });

            $translateProvider.translations('FR', {
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
                'FR': 'Français',
                'DE': 'Deutsch',
                'EN': 'English',
                'orderCompleted': 'La commande a été envoyée.',
                'profile': 'Profile',
                'previousOrders': 'Commandes précédentes',
                'noOrdersJet': 'Jusqu\'ici envoyé pas des ordres.',
                'orderOf': 'Commande du',
                'linesInOrder': 'produit(s) commandés'
            });

            $translateProvider.preferredLanguage('DE');
            $translateProvider.useSanitizeValueStrategy(null);
        });

})(maribelle.translations || (maribelle.translations = {}));