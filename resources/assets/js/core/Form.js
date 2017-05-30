import Errors from './Errors.js'; 

class Form {

    constructor(data) {
        this.originalData = data;

        for(let field in data) {
            this[field] = this.hasOldValue(field) ? this.getOldValue(field) : data[field];
        }

        this.errors = new Errors();
        this.errors.recordInitialFormErrors();
    }

    hasOldValue(field) {
        if(_.includes(field, 'password')) return;

        if(_.has(window, 'form_old_inputs')) {
            return window.form_old_inputs.hasOwnProperty(field);
        }

        return false;
    }

    getOldValue(field) {
        return window.form_old_inputs[field];
    }

    reset() {
        for(let field in this.originalData) {
            this[field] = '';
        }

        this.errors.clear();
    }

    data() {
        let data = Object.assign({}, this);

        delete data.originalData;
        delete data.errors;

        return data;
    }

    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
            .then(response => {
                this.onSuccess(response.data);

                resolve(response.data);
            })
            .catch(error => {
                this.onFail(error.response.data);

                reject(error.response.data);
            });
        });
    }

    onSuccess(data) {
        this.reset();
    }

    onFail(errors) {
        this.errors.record(errors);
    }
}

export default Form;
