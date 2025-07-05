import { ComponentFixture, TestBed } from '@angular/core/testing';

import { OngletEmployeComponent } from './onglet-employe.component';

describe('OngletEmployeComponent', () => {
  let component: OngletEmployeComponent;
  let fixture: ComponentFixture<OngletEmployeComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [OngletEmployeComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(OngletEmployeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
