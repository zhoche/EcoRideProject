import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RechercheCovoitComponent } from './recherche-covoit.component';

describe('RechercheCovoitComponent', () => {
  let component: RechercheCovoitComponent;
  let fixture: ComponentFixture<RechercheCovoitComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RechercheCovoitComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RechercheCovoitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
