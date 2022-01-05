import Fieldtype from './components/StatamicSecretFieldtype';

Statamic.booting(() => {
    Statamic.$components.register('secret_field-fieldtype', Fieldtype);
});
