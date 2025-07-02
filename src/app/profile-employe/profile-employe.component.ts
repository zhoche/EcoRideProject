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
  selectedTab: 'pending' | 'archived' = 'pending';
  reviews: any[] = [];
  archivedReviews: any[] = [];
  selectedReview: any = null;

  constructor(
    private reviewService: ReviewService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.reviewService.getPendingReviews().subscribe(data => {
      this.reviews = data.filter(r => r.status === 'à traiter' || r.status === 'en attente');
      this.archivedReviews = data.filter(r => r.status === 'validé' || r.status === 'refusé');
    });
  }

  onAction(id: number, action: 'approve' | 'reject') {
    console.log(`Action triggered: ${action} on ID: ${id}`);
    this.reviewService.authorizeFeedback(id, action).subscribe(() => {
      this.selectedReview = null;
      this.selectedTab = 'archived';
  
      this.reviewService.getPendingReviews().subscribe(data => {
        this.reviews = data.filter(r => r.status === 'à traiter' || r.status === 'en attente');
        this.archivedReviews = data.filter(r => r.status === 'validé' || r.status === 'refusé');
      });
    });
  }

  onTabChange(tab: 'pending' | 'archived') {
    this.selectedTab = tab;
    this.selectedReview = null;
    this.loadReviews();
  }


  loadReviews(): void {
    if (this.selectedTab === 'pending') {
      this.reviewService.getPendingReviews().subscribe(data => {
        this.reviews = data.filter(r => r.status === 'à traiter' || r.status === 'en attente');
      });
    } else {
      this.reviewService.getArchivedReviews().subscribe(data => {
        this.archivedReviews = data.filter(r => r.status === 'validé' || r.status === 'refusé');
      });
    }
  }


  onSelectReview(review: any) {
    this.selectedReview = review;
  }

  createStars(count: number): number[] {
    return Array(count).fill(0);
  }

  logout() {
    this.authService.logout();
    this.router.navigate(['/connexion']);
  }
}