import { Component, Input, OnInit, ElementRef, Renderer2, } from '@angular/core';

@Component({
  selector: '[app-shift-cell]',
  templateUrl: './shift-cell.component.html',
  styleUrls: ['./shift-cell.component.css']
})
export class ShiftCellComponent implements OnInit {
  @Input() shiftType: string;
  className: string;
  constructor(private elRef: ElementRef, private renderer: Renderer2) { }

  ngOnInit() {
      switch (this.shiftType) {
        case 'a' : this.className = 'aShiftColor';
                   break;
        case 'b' :
        case 'b1': this.className = 'bShiftColor';
                   break;
        case 'c' : this.className = 'cShiftColor';
                   break;
        case 'd' :
        case 'd1':
        case 'd2':
        case 'd3': this.className = 'dShiftColor';
                   break;
        case 'O' : this.className = 'oShiftColor';
                   break;
      }
      if (this.className !== '') {
        this.renderer.addClass( this.elRef.nativeElement, this.className);
      }
  }
}
