// using JavaScript instead of jQuery. Woot!

var triggerContainer = document.getElementById('triggerContainer'),
	topNav = document.getElementById('top-nav'),
	triggerInner = document.createElement('i'),
	triggerInnerText = document.createTextNode('menu');
	triggerInner.appendChild(triggerInnerText);

var trigger = document.createElement('a');
	trigger.className = 'menu-trigger';
	trigger.appendChild(triggerInner);


// console.log(trigger);
triggerContainer.appendChild(trigger);


function toggle(){
	topNav.classList.toggle("is-open");
}

trigger.addEventListener('click', function() {
    toggle();
}, false);
