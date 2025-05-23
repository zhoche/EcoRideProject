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
    private auth: AuthService,
    private router: Router
  ) {}

  onSubmit() {
    this.auth.login(this.email, this.password)
      .subscribe({
        next: user => {
          // redirection après succès
          this.router.navigate(['/profile-driver']);
        },
        error: err => {
          this.errorMsg = 'Identifiants invalides';
        }
      });
  }
}
