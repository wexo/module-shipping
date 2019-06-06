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
            if (modal()) {
                component(componentInstance);
                modal().openModal();

                if (onOpen) {
                    onOpen(modal().element);
                }
            }
        },

        close: function() {
            if (modal()) {
                component() && component().onModalClose && component().onModalClose(modal());
                component(null);
                modal().closeModal();
            }
        }
    };
});