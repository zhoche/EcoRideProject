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
      next: (response) => {
        console.log('✅ Réponse reçue :', response);
      
        const token = response.token;
        const user = response.user;
      
        // Protection contre l'absence de user
        if (!user) {
          this.errorMsg = "L'utilisateur n’a pas été trouvé dans la réponse.";
          return;
        }
      
        // Enregistre le token et l'utilisateur
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify(user));
      
        const role = user.role.replace('ROLE_', '').toLowerCase();
        console.log('🎭 Rôle détecté :', role);
      
        switch (role) {
          case 'passenger':
            this.router.navigate(['/profile-passenger']);
            break;
          case 'driver':
            this.router.navigate(['/profile-driver']);
            break;
          case 'employe':
            this.router.navigate(['/profile-employe']);
            break;
          case 'admin':
            this.router.navigate(['/profile-admin']);
            break;
          default:
            this.router.navigate(['/']);
        }
      },
    
      // ⬇️ Cette virgule était manquante
      error: (err) => {
        this.errorMsg = 'Erreur de connexion : ' + (err.error?.error || 'Veuillez réessayer.');
      }
    });    
  }
  
  
}
