class Errors {
    constructor() {
        this.errors = {};
    }

    get(field) {
        if(this.errors[field]) {
            return this.errors[field][0];
        }
    }

    record(errors) {
        this.errors = errors;
    }

    recordInitialFormErrors() {
        if(_.has(window, 'form_errors')) {
            this.record(window.form_errors);
        }
    }

    clear(field) {
        if(field) {
            delete this.errors[field];

            return;
        }
        this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any() {
        return ! _.isEmpty(this.errors);
    }
}

export default Errors;
