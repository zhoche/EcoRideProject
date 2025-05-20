import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RideValidatedComponent } from './ride-validated.component';

describe('RideValidatedComponent', () => {
  let component: RideValidatedComponent;
  let fixture: ComponentFixture<RideValidatedComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RideValidatedComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RideValidatedComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
