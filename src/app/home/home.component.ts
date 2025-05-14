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
