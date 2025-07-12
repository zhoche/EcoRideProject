import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth.service'; 
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; 
import { NgClass } from '@angular/common';



@Component({
  selector: 'app-inscription',
  standalone: true,
  templateUrl: './inscription.component.html',
  styleUrl: './inscription.component.scss',
  imports: [FormsModule, CommonModule, NgClass],
})
export class InscriptionComponent {
  email = '';
  password = '';
  passwordValidations = {
    minLength: false,
    hasLetter: false,
    hasNumber: false,
    hasSpecialChar: false
  };
  pseudo = '';
  role = 'ROLE_USER';
  gender: string = 'NO';
  message = '';


  constructor(private authService: AuthService, private router: Router) {}

  onSubmit() {
    this.authService.register({
      email: this.email,
      password: this.password,
      pseudo: this.pseudo,
      roles: [this.role],
      gender: this.gender
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



  //MOT DE PASSE


  isStrong = false;

  checkPasswordStrength() {
    const pwd = this.password;
    this.passwordValidations = {
      minLength: pwd.length >= 8,
      hasLetter: /[A-Za-z]/.test(pwd),
      hasNumber: /\d/.test(pwd),
      hasSpecialChar: /[^A-Za-z\d]/.test(pwd)
    };
  
    this.isStrong = Object.values(this.passwordValidations).every(Boolean);
  }

  getPasswordInputClass(): string {
    const v = this.passwordValidations;
    const touched = this.password.length > 0;
  
    if (!touched) return '';
    return v.minLength && v.hasLetter && v.hasNumber && v.hasSpecialChar
      ? 'input-valid'
      : 'input-invalid';
  }
  
}

