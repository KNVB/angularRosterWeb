import { HttpClientModule } from '@angular/common/http';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ShiftCellComponent } from 'src/app/components/shift-cell/shift-cell.component';
import { ITORosterRowComponent } from 'src/app/components/itoroster-row/itoroster-row.component';
import { RosterTableComponent } from 'src/app/components/roster-table/roster-table.component';
import {ToArrayPipe } from 'src/app/pipe/toArrayPipe';
import { RosterBodyComponent } from 'src/app/components/roster-body/roster-body.component';
@NgModule({
  declarations: [ITORosterRowComponent, RosterTableComponent, RosterBodyComponent, ShiftCellComponent,  ToArrayPipe],
  imports: [
    CommonModule,
    HttpClientModule,
  ],
  exports: [RosterTableComponent]
})
export class RosterTableModule {}
