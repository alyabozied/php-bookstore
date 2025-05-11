document.querySelectorAll('.slider').forEach(element => {
    bindSliderEvents(element);
});


function bindSliderEvents(slider) {
    const leftButton = slider.querySelector('.left-button');
    const rightButton = slider.querySelector('.right-button');

    leftButton.addEventListener('click', (e) => {
        e.target.disabled = true;
        setTimeout(() => {
            e.target.disabled = false;
        }, 500);
        moveSlider('left', slider);
    });

    rightButton.addEventListener('click', (e) => {
        e.target.disabled = true;
        setTimeout(() => {
            e.target.disabled = false;
        }, 500);
        moveSlider('right', slider);
    });
}

const moveSlider = (direction, slider) => { 
    const scrollAmount = direction === 'left' ? -slider.offsetWidth : slider.offsetWidth;
    slider.scrollLeft += scrollAmount;
};   
