<?php
    
    
    class Renderer{
        
        /**
         * Render statistic data to table
         */
        public static function render($statistic)
        {
            $html = '<table>
                <thead  style="background: #fc0">
                    <tr>
                        <td>Customer Id</td>
                        <td>Number of calls within the same continent</td> 
                        <td>Total Duration of calls within the same continent</td>
                        <td>Total number of all calls</td> 
                        <td>The total duration of all calls</td> 
                    </tr> 
                </thead><tbody style="background: #ccc">';
            foreach($statistic as $customer){
                $html .= "<tr>
                    <td>$customer->customerId</td>
                    <td>$customer->sameContinentCallsNumber</td>
                    <td>$customer->sameContinentCallsDuration</td>
                    <td>$customer->totalCallsNumber</td>
                    <td>$customer->totalCallsDuration</td>
                </tr>";
            }
        
            $html .= '</tbody></table> 
                <br><a href='/'>Back</a>';

            return $html;
        }
    }