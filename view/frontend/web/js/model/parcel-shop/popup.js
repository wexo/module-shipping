define([
    'ko'
], function(ko) {

    var modal = ko.observable(null);
    var component = ko.observable(null);

    return {
        modal: modal,
        component: component,

        setModal: function(modalInstance) {
            modal(modalInstance);
            return this;
        },

        open: function(componentInstance, onOpen) {
            if(modal()) {
                component(componentInstance);
                modal().element.trigger('openModal');

                if(onOpen) {
                    onOpen(modal().element);
                }
            }
        }
    }
});