import createElement from './element.js';

const errorsData = [
    'Моля попълнете всички полета, маркирани със "*"!',
    'Неуспешно запазване!',
    'Моля качете валиден файл!',
    'Файлът не беше качен успешно!',
    'Грешка при генериране на файл!',
    'Невалиден тип на анотацията!'
]

const parseMessage = (message) => {
    const errors = [];
    const getErrors = (val) => {
        if(val === 'Field should not be empty' && !errors.includes(errorsData[0])) {
            errors.push(errorsData[0]);
        }
        if(val === 'Error when trying to save' && !errors.includes(errorsData[1])) {
            errors.push(errorsData[1]);
        }
        if(val === 'Error when trying to import citations' && !errors.includes(errorsData[2])) {
            errors.push(errorsData[2]);
        }
        if(val === 'File is not uploaded' && !errors.includes(errorsData[3])) {
            errors.push(errorsData[3]);
        }
        if(val === 'Error when trying to export citations' && !errors.includes(errorsData[4])) {
            errors.push(errorsData[4]);
        }
        if(val === 'Ivalid annotation type!' && !errors.includes(errorsData[4])) {
            errors.push(errorsData[5]);
        }
    }

    if (typeof message === 'string') {
        getErrors(message);
    } else if (typeof message?.message === 'string') {
        getErrors(message.message);
    } else {
        Object.values(message).forEach(element => {
            getErrors(element);
        });
    }

    if(!errors.length) {
        errors.push('Грешка!');
    }

    return errors.join('<br>');
}

const errorMessage = (message) => {
    const errorMessage = parseMessage(message);

    const errorData = {
        tagName: 'div',
        attributes: [
            {name: 'id', value: 'error-message'},
            {name: 'class', value: 'errorMessage'}
        ],
        properties: [
            {name: 'innerHTML', value: errorMessage}
        ]
    }
    const error = createElement(errorData);

    document.body.prepend(error);
};

export default errorMessage;