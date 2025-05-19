import { Component, EventEmitter, Input, Output, ViewEncapsulation } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-modal',
  standalone: true, 
  imports: [CommonModule],
  templateUrl: './modal.component.html',
  styleUrl: './modal.component.scss',
  encapsulation: ViewEncapsulation.None,
})
export class ModalComponent {

  /** Controls whether the modal is visible */
  @Input() visible = false;
  /** Emet un événement pour fermer */
  @Output() close = new EventEmitter<void>();
  onBackdropClick() {this.close.emit();}

}
