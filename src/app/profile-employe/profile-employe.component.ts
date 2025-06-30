import { Component, OnInit } from '@angular/core';
import { ReviewService } from '../review.service';
import { CommonModule } from '@angular/common'; 
import { AuthService } from '../auth.service';
import { Router } from '@angular/router';



@Component({
  selector: 'app-profile-employe',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './profile-employe.component.html',
  styleUrls: ['./profile-employe.component.scss']
})
export class ProfileEmployeComponent implements OnInit {
  reviews: any[] = [];

  constructor(
    private reviewService: ReviewService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.reviewService.getPendingReviews().subscribe(data => {
      this.reviews = data;
    });
  }

  onAction(id: number, action: 'approve' | 'reject') {
    this.reviewService.authorizeFeedback(id, action).subscribe(() => {
      this.reviewService.getPendingReviews().subscribe(updated => {
        this.reviews = updated;
      });
    });
  }

  createStars(count: number): number[] {
    return Array(count).fill(0);
  }

  
  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}
