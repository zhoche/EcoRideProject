import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RideReportComponent } from './ride-report.component';

describe('RideReportComponent', () => {
  let component: RideReportComponent;
  let fixture: ComponentFixture<RideReportComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RideReportComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RideReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
