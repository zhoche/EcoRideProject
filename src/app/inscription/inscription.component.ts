import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service'; 
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-inscription',
  templateUrl: './inscription.component.html',
  styleUrl: './inscription.component.scss',
  imports: [FormsModule],
})
export class InscriptionComponent {
  email = '';
  password = '';
  pseudo = '';
  role = 'ROLE_USER';
  message = '';

  constructor(private authService: AuthService, private router: Router) {}

  onSubmit() {
    this.authService.register({
      email: this.email,
      password: this.password,
      pseudo: this.pseudo,
      roles: [this.role]
    }).subscribe({
      next: () => {
        alert('Votre compte a bien été créé ! ✅');
        this.message = 'Inscription réussie !';
        this.router.navigate(['/connexion']);
      },
      error: err => {
        this.message = 'Erreur : ' + (err.error?.message || 'Veuillez réessayer.');
        console.error(err);
      }
    });
  
    console.log('Formulaire soumis !');
  }

}

