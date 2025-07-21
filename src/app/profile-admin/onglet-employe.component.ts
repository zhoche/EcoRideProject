import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpHeaders, HttpClientModule } from '@angular/common/http';
import { ReviewService } from '../review.service';
import { environment } from '../../environments/environment';

@Component({
  selector: 'app-onglet-employe',
  standalone: true,
  templateUrl: './onglet-employe.component.html',
  styleUrls: ['./onglet-employe.component.scss'],
  imports: [CommonModule, ReactiveFormsModule, HttpClientModule]
})
export class OngletEmployeComponent implements OnInit {
  employeeForm!: FormGroup;
  employees: any[] = [];
  selectedReview: any;
  suspendedEmails: string[] = [];

  private api = `${environment.apiUrl}/api`;

  private headers = new HttpHeaders({
    Authorization: `Bearer ${localStorage.getItem('token')}`
  });

  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private reviewService: ReviewService
  ) {}

  ngOnInit(): void {
    this.employeeForm = this.fb.group({
      email:    ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
      pseudo:   ['', Validators.required]
    });

    this.fetchEmployees();
  }

  onSelectReview(review: any) {
    this.selectedReview = review;
  }

  fetchEmployees(): void {
    // 1) On récupère d'abord la liste des emails suspendus
    this.http.get<string[]>(`${this.api}/admin/suspended-emails`, { headers: this.headers })
      .subscribe({
        next: suspended => {
          this.suspendedEmails = suspended;

          // 2) Puis la liste des employés
          this.http.get<any[]>(`${this.api}/admin/employee-list`, { headers: this.headers })
            .subscribe({
              next: data => {
                this.employees = data.map(user => ({
                  ...user,
                  createdAt: user.createdAt ? new Date(user.createdAt) : null,
                  status: this.suspendedEmails.includes(user.email) ? 'suspendu' : 'actif'
                }));
              },
              error: err => console.error('Erreur employés :', err)
            });
        },
        error: err => console.error('Erreur emails suspendus :', err)
      });
  }

  onSubmit(): void {
    if (!this.employeeForm.valid) return;

    const payload = {
      ...this.employeeForm.value,
      roles:   ['ROLE_EMPLOYE'],
      credits: 0
    };

    this.http.post(`${this.api}/register`, payload, { headers: this.headers })
      .subscribe({
        next: () => {
          alert('✅ Compte employé créé !');
          this.employeeForm.reset();
          this.fetchEmployees();
        },
        error: () => alert('❌ Une erreur est survenue.')
      });
  }

  deleteEmployee(id: number): void {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce compte employé ?')) return;

    this.http.delete(`${this.api}/admin/employee-delete/${id}`, { headers: this.headers })
      .subscribe({
        next: () => {
          alert('🗑️ Compte employé supprimé avec succès');
          this.fetchEmployees();
        },
        error: err => {
          console.error('Erreur lors de la suppression :', err);
          alert('❌ Une erreur est survenue');
        }
      });
  }
}
