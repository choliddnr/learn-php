
import './style.scss';
import { Popover, Tooltip }from 'bootstrap';

 let isMoreFeaturesOpen = false;



const toggleMoreFeatures = () => {
    const moreFeatures = document.querySelectorAll('.more-features');
    [...moreFeatures].forEach((features) => {
        if (isMoreFeaturesOpen) {
            features.classList.add('d-none')

        } else {
            features.classList.remove('d-none')
        }
    })
    isMoreFeaturesOpen = !isMoreFeaturesOpen
}



const track = document.getElementById('carouselTrack');
const cards = track.querySelectorAll('.carousel-card');


let currentIndex = (cards.length / 2) - 1;

function centerCard(index) {
    const card = cards[index];
    const offset = (card.offsetLeft + card.offsetWidth / 2) - (track.offsetWidth / 2);
    track.scrollTo({
        left: offset,
        behavior: 'smooth'
    });
}

function isFullyVisible(card) {
    const trackRect = track.getBoundingClientRect();
    const cardRect = card.getBoundingClientRect();
    return (
        cardRect.left >= trackRect.left &&
        cardRect.right <= trackRect.right
    );
}
window.addEventListener('load', async () => {

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');

    if (isFullyVisible(cards[0]) && isFullyVisible(cards[cards.length - 1])) {
        track.classList.add('justify-content-center')
        prevBtn.classList.add('d-none');
        nextBtn.classList.add('d-none');
    } else {
        centerCard(currentIndex);
        prevBtn.addEventListener('click', () => {

            if (currentIndex > 0 && !isFullyVisible(cards[0])) {
                currentIndex--;
                centerCard(currentIndex);
            }
        });

        nextBtn.addEventListener('click', () => {
            const len = cards.length - 1
            if (currentIndex < len && !isFullyVisible(cards[len])) {
                currentIndex++;
                centerCard(currentIndex);
            }
        });
    }
});


const menus = document.getElementById('menus');
const menu_list = document.querySelectorAll('.menu');
const backdrop = document.getElementById('backdrop');



const closeMenuPopover = () => {
    backdrop.classList.add('d-none')
    menus.setAttribute('menu-id', '');
    menus.innerHTML = "";
    menus.classList.add('d-none')
    const icon = document.querySelector(".down.d-none")
    if(icon){
        const menu = icon.closest(".menu")
        menu.classList.remove('text-lp-neutral-400')
        menu.querySelector('.up').classList.add('d-none')
        icon.classList.remove('d-none')

    }
}

menu_list.forEach(el => {
    el.addEventListener('click', (e) => {
        const menu = e.target.closest(".menu")
        menu.classList.add('text-lp-neutral-400')
        const menu_id = menu.getAttribute('menu-id');
        if (menus.getAttribute('menu-id') !== menu_id) {
            closeMenuPopover()
            backdrop.classList.remove('d-none')
            menus.setAttribute('menu-id', menu_id);
            menus.innerHTML = menu.querySelector('.menu-content').innerHTML;
            menus.classList.remove('d-none')
            menu.querySelector('.up.d-none').classList.remove('d-none')
            menu.querySelector('.down').classList.add('d-none')
        } else {
            closeMenuPopover()
        }

    });
});

document.addEventListener('click', (e) => {
    const cliked_menu = Array.from(menu_list).some(el => el.contains(e.target));
    if (!menus.contains(e.target) && !cliked_menu) closeMenuPopover()
})

const setOffcanvasBody = (content_id)=>{
    const oc_body = document.querySelector(".offcanvas-body")
    oc_body.innerHTML = document.getElementById(content_id).innerHTML
}
setOffcanvasBody('main-menu')
window.setOffcanvasBody = setOffcanvasBody
// const oc_menus_links = document.querySelectorAll('.menu-link')
// oc_menus_links.forEach(el=>{
//     el.addEventListener('click', e=>{
//         setOffcanvasBody(el.getAttribute("menu"))
//         })
// })

// const back_to_main_menu = document.querySelectorAll(".back-to-main-menu")
// back_to_main_menu.forEach(el=>{
//     el.addEventListener('click', e=>{
//         setOffcanvasBody('main-menu')
//         })
// })


window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 0) {
        navbar.classList.add('shadow-lg');
    } else {
        navbar.classList.remove('shadow-lg');
    }
});

const popoverTriggerListHero = document.querySelectorAll('#hero span[data-bs-toggle="popover"]')
const popoverListHero = [...popoverTriggerListHero].map(popoverTriggerEl => new Popover(popoverTriggerEl,  {
    popperConfig: {
        placement: "right-start"
    }
}))

const popoverTriggerHemat = document.querySelectorAll('#hemat span[data-bs-toggle="popover"]')
const popoverHemat = [...popoverTriggerHemat].map(popoverTriggerEl => new Popover(popoverTriggerEl,  {
    popperConfig: {
        placement: "right-start"
    }
}))

const popoverTriggerListPricing = document.querySelectorAll('#pricing span[data-bs-toggle="popover"]')
const popoverListPricing = [...popoverTriggerListPricing].map(popoverTriggerEl => new Popover(popoverTriggerEl,  {
    popperConfig: {
        placement: "bottom-start"
    }
}))

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl))

