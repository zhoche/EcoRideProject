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
      next: user => {
        console.log('✅ Connexion réussie', user);
  
        switch (user.role) {
          case 'admin':
            this.router.navigate(['/profile-admin']);
            break;
          case 'driver':
            this.router.navigate(['/profile-driver']);
            break;
          case 'passenger':
            this.router.navigate(['/profile-passenger']);
            break;
          case 'employe':
            this.router.navigate(['/profile-employe']);
            break;
          default:
            this.router.navigate(['/profile-passenger']);
        }
      },
      error: err => {
        this.errorMsg = 'Erreur de connexion : ' + (err.error?.error || 'Veuillez réessayer.');
      }
    });
  
    console.log('➡️ Données envoyées :', this.email, this.password);
  }
  
  
}
