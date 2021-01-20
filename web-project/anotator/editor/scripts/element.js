const createElement = (data) => {
    if(!data || !data.tagName) {
        return;
    }

    const element = document.createElement(data.tagName);

    data.attributes?.forEach(attribute => {
        element.setAttribute(attribute.name, attribute.value);
    });

    data.properties?.forEach(property => {
        element[property.name] = property.value;
    });

    data.style?.forEach(style => {
        element.style[style.name] = style.value; 
    });

    if(data.eventListeners?.length) {
        setEventListeners(element, data.eventListeners);
    }

    if (data.options) {
        setElementOptions(element, data.options);
    }

    return element;
}

const setElementOptions = (element, options) => options.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option.value;
        optionElement.text = option.value;
        element.appendChild(optionElement);
    });

const setEventListeners = (element, listeners) => listeners.forEach(listener => element.addEventListener(listener.event, listener.listener));

export default createElement;