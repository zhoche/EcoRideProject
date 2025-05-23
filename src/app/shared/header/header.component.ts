import { Component, OnInit } from '@angular/core';
import { AuthService, User } from '../../auth.service';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive }   from '@angular/router';


@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive],  
  templateUrl: './header.component.html',
  styleUrl: './header.component.scss'
})
export class HeaderComponent implements OnInit {
  role: 'passenger'|'driver'|'employe'|'admin'|'guest' = 'guest';

  constructor(private auth: AuthService) {}

  ngOnInit() {
    this.auth.user$.subscribe((u: User | null) => {
      this.role = u?.role ?? 'guest';
    });
    
  }
}

