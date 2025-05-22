import { Component, OnInit } from '@angular/core';
import { AuthService } from '../auth.service';
import { Observable } from 'rxjs';
import { RouterLink, RouterLinkActive }   from '@angular/router';


@Component({
  selector: 'app-header',
  standalone: true,
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './header.component.html',
  styleUrl: './header.component.scss'
})
export class HeaderComponent {
  // on expose l’Observable pour le template via le pipe async
  isLoggedIn$!: Observable<boolean>;

  constructor(private auth: AuthService) {}

  ngOnInit() {
    // ici auth est déjà injecté
    this.isLoggedIn$ = this.auth.isLoggedIn$;
  }
}
