const accordionHeaders = document.querySelectorAll('.accordion-item-header');

accordionHeaders.forEach(header => {
    header.addEventListener('click', function (e) {
        const accordionItem = header.parentElement;
        const accordionContent = accordionItem.querySelector('.accordion-content');

        if (accordionItem.classList.contains('open')) {
            accordionItem.classList.remove('open');
            accordionContent.style.maxHeight = '0';
        } else {
            // Close all other open accordions
            document.querySelectorAll('.accordion-item.open').forEach(openAccordionItem => {
                const openAccordionContent = openAccordionItem.querySelector('.accordion-content');
                openAccordionItem.classList.remove('open');
                openAccordionContent.style.maxHeight = '0';
            });

            // Open the clicked accordion
            accordionItem.classList.add('open');
            //change the height to the 'wanted' height in px
            accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
        }
    });
});
