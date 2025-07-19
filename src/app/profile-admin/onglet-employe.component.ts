import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ReviewService } from '../review.service';


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


  constructor(
    private fb: FormBuilder,
    private http: HttpClient,
    private reviewService: ReviewService
  ) {}


ngOnInit(): void {
  this.employeeForm = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', Validators.required],
    pseudo: ['', Validators.required]
  });

  this.fetchEmployees();
}


onSelectReview(review: any) {
  this.selectedReview = review;
}


fetchEmployees(): void {
  this.http.get<string[]>('/api/admin/suspended-emails', {
    headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
  }).subscribe({
    next: suspended => {
      this.suspendedEmails = suspended;

      this.http.get<any[]>('https://ecoride-back-xm7y.onrender.com/api/admin/employee-list', {
        headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
      }).subscribe({
        next: data => {
          this.employees = data.map(user => ({
            ...user,
            createdAt: user.createdAt ? new Date(user.createdAt) : null
            // status: this.suspendedEmails.includes(user.email) ? 'suspendu' : 'actif' // optionnel
          }));
        },
        error: err => console.error('Erreur employés :', err)
      });
    },
    error: err => console.error('Erreur emails suspendus :', err)
  });
}



onSubmit(): void {
  if (this.employeeForm.valid) {
    const formData = {
      ...this.employeeForm.value,
      roles: ['ROLE_EMPLOYE'], 
      credits: 0
    };

    this.http.post('/api/register', formData).subscribe({
      next: () => {
        alert('✅ Compte employé créé !');
        this.employeeForm.reset();
        this.fetchEmployees();
      },
      error: () => alert('❌ Une erreur est survenue.')
    });
  }
}



deleteEmployee(id: number): void {
  const confirmed = confirm('Êtes-vous sûr de vouloir supprimer ce compte employé ?');

  if (confirmed) {
    this.http.delete(`https://ecoride-back-xm7y.onrender.com/api/admin/employee-delete/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`
      }
    }).subscribe({
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


}
