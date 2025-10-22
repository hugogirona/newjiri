export default class ContactRoleToggler {
    constructor(checkboxSelector = '[data-contact-checkbox]', roleSelector = 'data-contact-role') {
        this.checkboxSelector = checkboxSelector;
        this.roleSelector = roleSelector; // Sans les crochets
        this.init();
    }

    init() {
        const checkboxes = document.querySelectorAll(this.checkboxSelector);

        if (checkboxes.length === 0) {
            return;
        }

        checkboxes.forEach(checkbox => {
            this.attachEventListener(checkbox);
            this.initializeState(checkbox);
        });
    }

    attachEventListener(checkbox) {
        checkbox.addEventListener('change', () => {
            this.toggleRole(checkbox);
        });
    }

    toggleRole(checkbox) {
        const contactId = checkbox.dataset.contactCheckbox;
        const select = document.querySelector(`[${this.roleSelector}="${contactId}"]`);

        if (!select) {
            console.warn(`No role select found for contact ID: ${contactId}`);
            return;
        }

        if (checkbox.checked) {
            this.enableSelect(select);
        } else {
            this.disableSelect(select);
        }
    }

    enableSelect(select) {
        select.disabled = false;
        select.required = true;
    }

    disableSelect(select) {
        select.disabled = true;
        select.required = false;
        select.value = '';
    }

    initializeState(checkbox) {
        if (checkbox.checked) {
            const contactId = checkbox.dataset.contactCheckbox;
            const select = document.querySelector(`[${this.roleSelector}="${contactId}"]`);

            if (select) {
                this.enableSelect(select);
            }
        }
    }
}
