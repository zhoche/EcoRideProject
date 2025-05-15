import { Component } from '@angular/core';
import { SearchBarComponent } from '../search-bar/search-bar.component';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [SearchBarComponent],
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})
export class HomeComponent {

}


//ANIMATION SOULIGNEMENT CONFIANCE
document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.underline-animate');

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
        }
      });
    },
    { threshold: 0.5 }
  );

  elements.forEach(el => observer.observe(el));
});



//ANIMATION IMAGES SCROLL 
document.addEventListener('DOMContentLoaded', () => {
  const elements = document.querySelectorAll('.animate-on-scroll');

  const observer = new IntersectionObserver(
    entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
        }
      });
    },
    { threshold: 0.2 }
  );

  elements.forEach(el => observer.observe(el));
});



//ANIMATION CHIFFRES CLES 
document.addEventListener('DOMContentLoaded', () => {
  const counters = document.querySelectorAll<HTMLElement>('.impact-value');

  const animateCounters = () => {
    counters.forEach(counter => {
      const target = +counter.getAttribute('data-target')!;
      const rawText = counter.textContent || '';
      const suffix = rawText.replace(/[0-9]/g, '');

      console.log(`→ ${target}${suffix}`); // debug

      let current = 0;
      const increment = target / 100;

      const updateCount = () => {
        current += increment;
        if (current < target) {
          counter.textContent = Math.floor(current) + suffix;
          requestAnimationFrame(updateCount);
        } else {
          counter.textContent = target + suffix;
        }
      };

      updateCount();
    });
  };

  let hasAnimated = false;


  //Afficher l'animation uniquement au scroll 
  const observer = new IntersectionObserver(entries => {
    if (!hasAnimated && entries.some(e => e.isIntersecting)) {
      animateCounters();
      hasAnimated = true;
    }
  }, { threshold: 0.4 });
  
  counters.forEach(counter => observer.observe(counter));
  
});



//BOUTONS FAQ
document.addEventListener('DOMContentLoaded', () => {
  const toggles = document.querySelectorAll('.faq-toggle');

  toggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
      toggle.classList.toggle('active');
    });
  });
});
