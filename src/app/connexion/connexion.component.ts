// src/app/connexion/connexion.component.ts
import { Component, OnInit }   from '@angular/core';
import { CommonModule }        from '@angular/common';
import { FormsModule }         from '@angular/forms';
import { RouterLink }          from '@angular/router';
import { AuthService }         from '../auth.service';
import { UserApiService }      from '../services/user-api.service';

@Component({
  selector: 'app-connexion',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './connexion.component.html',
  styleUrls: ['./connexion.component.scss']
})
export class ConnexionComponent implements OnInit {
  email    = '';
  password = '';
  errorMsg = '';

  constructor(
    private authService: AuthService,
    private userApi: UserApiService  
  ) {}

  ngOnInit(): void {
  }

  onSubmit() {
    const credentials = { email: this.email, password: this.password };

    this.authService.login(credentials).subscribe({
      next: () => {
        this.authService.redirectUserAfterLogin();
      },
      error: (err) => {
        this.errorMsg = 'Erreur de connexion : ' +
          (err.error?.error || 'Veuillez réessayer.');
      }
    });
  }
}
