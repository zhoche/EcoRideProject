// src/app/connexion/connexion.component.ts
import { Component }  from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule }  from '@angular/forms';
import { Router }      from '@angular/router';
import { AuthService } from '../auth.service';
import { RouterLink } from '@angular/router';


@Component({
  selector: 'app-connexion',
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './connexion.component.html',
  styleUrls: ['./connexion.component.scss']
})
export class ConnexionComponent {
  email    = '';
  password = '';
  errorMsg = '';

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  onSubmit() {
    const credentials = {
      email: this.email,
      password: this.password
    };
  
    this.authService.login(credentials).subscribe({
      next: () => {
        this.authService.redirectUserAfterLogin();
      },
      error: (err) => {
        this.errorMsg = 'Erreur de connexion : ' + (err.error?.error || 'Veuillez réessayer.');
      }
    });
  }

  
}

