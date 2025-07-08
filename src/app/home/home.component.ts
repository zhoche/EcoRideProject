import { SearchBarComponent } from '../search-bar/search-bar.component';
import { RouterLink }     from '@angular/router';
import { ViewportScroller } from '@angular/common';
import { AfterViewInit, Component } from '@angular/core';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [SearchBarComponent, RouterLink],
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})

export class HomeComponent implements AfterViewInit {
  constructor(private vps: ViewportScroller) {}

  goToSearch() {
    const el = document.getElementById('search-bar');
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  ngAfterViewInit(): void {
    this.initUnderlineAnimation();
    this.initScrollImageAnimation();
    this.initImpactCounters();
    this.initFaqToggles();
  }

  private initUnderlineAnimation() {
    const elements = document.querySelectorAll('.underline-animate');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
        }
      });
    }, { threshold: 0.5 });

    elements.forEach(el => observer.observe(el));
  }

  private initScrollImageAnimation() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
        }
      });
    }, { threshold: 0.2 });

    elements.forEach(el => observer.observe(el));
  }

  private initImpactCounters() {
    const counters = document.querySelectorAll<HTMLElement>('.impact-value');
    let hasAnimated = false;

    const animateCounters = () => {
      counters.forEach(counter => {
        const target = +counter.getAttribute('data-target')!;
        const rawText = counter.textContent || '';
        const suffix = rawText.replace(/[0-9]/g, '');
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

    const observer = new IntersectionObserver(entries => {
      if (!hasAnimated && entries.some(e => e.isIntersecting)) {
        animateCounters();
        hasAnimated = true;
      }
    }, { threshold: 0.4 });

    counters.forEach(counter => observer.observe(counter));
  }

  private initFaqToggles() {
    const toggles = document.querySelectorAll('.faq-toggle');

    toggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        toggles.forEach(other => {
          if (other !== toggle) {
            other.classList.remove('active');
            const answer = other.nextElementSibling as HTMLElement;
            if (answer) {
              answer.style.maxHeight = '0';
              answer.style.padding = '0';
            }
          }
        });

        toggle.classList.toggle('active');
        const answer = toggle.nextElementSibling as HTMLElement;

        if (toggle.classList.contains('active')) {
          answer.style.maxHeight = answer.scrollHeight + 'px';
          answer.style.padding = '0 0 1rem 0';
        } else {
          answer.style.maxHeight = '0';
          answer.style.padding = '0';
        }
      });
    });
  }
}
