// src/app/app.component.ts
import { Component }          from '@angular/core';
import { RouterOutlet }       from '@angular/router';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { HeaderComponent }    from './shared/header/header.component';
import { FooterComponent }    from './footer/footer.component';
import { environment }        from '../environments/environment';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    RouterOutlet,
    RouterLink,
    RouterLinkActive,
    HeaderComponent,
    FooterComponent
  ],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'EcoRide app';

  constructor() {
  }
}
