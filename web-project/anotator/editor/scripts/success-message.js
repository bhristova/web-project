import createElement from './element.js';

const successMessage = (message) => {
    const successData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: 'error-message'},
            {name: 'class', value: 'successMessage'}
        ],
        properties: [
            {name: 'innerHTML', value: message}
        ]
    }
    const success = createElement(successData);

    document.body.prepend(success);
};

export default successMessage;