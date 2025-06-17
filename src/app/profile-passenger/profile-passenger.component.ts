import { Component } from '@angular/core';
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-profile-passenger',
  imports: [],
  templateUrl: './profile-passenger.component.html',
  styleUrl: './profile-passenger.component.scss'
})
export class ProfilePassengerComponent {
  constructor(
    private authService: AuthService,
    private router: Router
  ) {}
  
  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
