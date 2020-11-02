# Projet IRIS

## Tests

### Tests unitaires

Exécuter tous les tests de l'application:

```sh
vendor/bin/phpunit
```

Exécuter seulement un fichier de test

```sh
vendor/bin/phpunit %CHEMION_VERS_MON_FICHIER%/%MON_FICHIER%
```

### Code Style

Vérifier tous les fichiers de l'applications:

```sh
vendor/bin/php-cs-fixer fix --dry-run -vv
```

Appliquer les fix sur tous les fichiers:

```sh
vendor/bin/php-cs-fixer fix
```
