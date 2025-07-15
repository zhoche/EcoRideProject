import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-ride-validated',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './ride-validated.component.html',
  styleUrls: ['./ride-validated.component.scss']
})
export class RideValidatedComponent implements OnInit {
  token: string = '';
  rideId: number | null = null;
  passengerName: string = '';
  tokenValid: boolean = false;
  selectedRating: number = 0;
  comment: string = '';
  stars: number[] = [1, 2, 3, 4, 5];

  constructor(private route: ActivatedRoute, private http: HttpClient) {}

  ngOnInit(): void {
    const queryToken = this.route.snapshot.queryParamMap.get('token');
    this.token = queryToken || 'abc123-token-fake-pour-test';
    this.checkTokenValidity(this.token);
  }

  checkTokenValidity(token: string): void {
    this.http.get<any>(`http://localhost:8000/api/rides/feedback/check?token=${token}`).subscribe({
      next: (res) => {
        this.rideId = res.ride_id;
        this.passengerName = res.passenger;
        this.tokenValid = true;
      },
      error: () => {
        alert('Lien invalide ou manquant.');
        this.tokenValid = false;
      }
    });
  }

  selectRating(value: number): void {
    this.selectedRating = value;
  }

  submitAvis(): void {
    if (!this.rideId || !this.selectedRating) {
      alert('Merci de noter le trajet.');
      return;
    }

    const avisPayload = {
      ride_id: this.rideId,
      rating: this.selectedRating,
      comment: this.comment
    };

    this.http.post('http://localhost:8000/api/rides/feedback', avisPayload).subscribe({
      next: () => alert('Merci pour votre retour !'),
      error: () => alert('Erreur lors de l’envoi de votre avis.')
    });
  }
}
